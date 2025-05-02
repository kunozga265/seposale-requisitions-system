<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\ProductionResource;
use App\Http\Resources\SiteResource;
use App\Http\Resources\StockResource;
use App\Models\Batch;
use App\Models\Damage;
use App\Models\Inventory;
use App\Models\Material;
use App\Models\Production;
use App\Models\Site;
use App\Models\MaterialsType;
use App\Models\Usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductionController extends Controller
{
    public function index(Request $request, $code)
    {


        $site = Site::where("code", $code)->first();

        if (is_object($site)) {

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new SiteResource($site));
            } else {
                $materials_types = MaterialsType::all();

                $usages = [];
                $productions = $site->productions()->orderBy("date", "desc")->get();
                foreach ($productions as $production) {
                    foreach ($production->usages as $usage) {
                        $usages[] = $usage;
                    }
                }

                $groupedUsages = array_reduce($usages, function ($carry, $item) {
                    $carry[$item['material_id']][] = $this->convertToArray(($item));
                    return $carry;
                }, []);

                $all = [];
                foreach ($groupedUsages as $groupedUsage) {
                    $name = $groupedUsage[0]["material"]["name"];

                    $usages = [];
                    foreach ($groupedUsage as $usage) {
                        $usages[] = $usage;
                    }

                    $all[] = [
                        "name" => $name,
                        "usages" => $usages,
                    ];
                }

                //Web Response
                return Inertia::render('Production/Index', [
                    'site' => $site,
                    'materials' => MaterialResource::collection($site->materials),
                    'materialsTypes' => $materials_types,
                    'productions' => ProductionResource::collection($productions),
                    'usages' => $all,
                    'inventories' => InventoryResource::collection($site->inventories()->where('producible', 1)->get()),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
    }

    public function store(Request $request, string $code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {

            $request->validate([
                "materials" => ["required"],
                "labour" => ["required"],
                "food" => ["required"],
                "date" => ["required"],
            ]);

            //check if quantities are available
            foreach ($request->materials as $materialObject) {
                $material = Material::find($materialObject["id"]);
                if ($materialObject["quantity"] > $material->quantity) {
                    return Redirect::back()->with("error", "{$material->name} is out of stock");
                }
            }

            //check if damages can be recorded
            $total_quantity = 0;
            foreach ($request->inventories as $inventoryObject) {
                $total_quantity += $inventoryObject["quantity"];

                if ($inventoryObject["damages"] > 0) {
                    $inventory = Inventory::find($inventoryObject["id"]);

                    if ($inventory->stock() >= $inventoryObject["damages"]) {
                        continue;
                    } else {
                        return Redirect::back()->with("error", "{$inventory->name} is out of stock cannot record damages");
                    }
                }
                
                if ($inventoryObject["quantity"] > 0) {
                    
                }
            }

            
            if ($total_quantity == 0) {
                return Redirect::back()->with("error", "No materials produced!");
            }

            // dd("". $total_quantity);

            

            $closing_stock = [];
            $opening_stock = [];

            foreach ($site->materials as $material) {
                $opening_stock[] = [
                    "id" => $material->id,
                    "name" => $material->name,
                    "quantity" => $material->quantity,
                    "units" => $material->units,
                    "type" => $material->type->slug,
                ];
            }

            $production = Production::create([
                "code" => $this->getCodeNumber(),
                "opening_stock" => json_encode($opening_stock),
                "closing_stock" => json_encode([]),
                "expenses" => json_encode([
                    "labour" => $request->labour,
                    "food" => $request->food,
                ]),
                "date" => $request->date,
                "user_id" => Auth::id(),
                "site_id" => $site->id,
            ]);

            // //load for cement
            // dump($request->cement);
            // $this->recordUsage(
            //     $request->cement["id"], 
            //     floatval($request->cement["quantity"]), 
            //     $request->date, 
            //     $production);

            $cost = 0;
            foreach ($request->materials as $materialObject) {
                $cost += $this->recordUsage(
                    $materialObject["id"],
                    $materialObject["quantity"],
                    $request->date,
                    $production
                );
               
            }

            //average cost
            $average_cost = ($cost + $request->labour + $request->food)/$total_quantity;

            foreach ($site->materials as $material) {
                $closing_stock[] = [
                    "id" => $material->id,
                    "name" => $material->name,
                    "quantity" => $material->quantity,
                    "units" => $material->units,
                    "type" => $material->type->slug,
                ];
            }

            $production->update([
                "closing_stock" => json_encode($closing_stock),
            ]);

            //Record materials produced
            foreach ($request->inventories as $inventoryObject) {
                $inventory = Inventory::find($inventoryObject["id"]);

                if ($inventoryObject["quantity"] > 0) {

                    $availableStock = $inventory->available_stock + $inventoryObject["quantity"];

                    $inventory->update([
                        'available_stock' => $availableStock
                    ]);

                    Batch::create([
                        "date" => $request->date,
                        "price" =>  $average_cost,
                        "quantity" => $inventoryObject["quantity"],
                        "balance" =>  $inventoryObject["quantity"],
                        "ready_date" =>  $inventoryObject["date"],
                        "photo" => null,
                        "comments" => "",
                        "inventory_id" => $inventory->id,
                        "production_id" => $production->id,
                        "user_id" => Auth::id(),
                    ]);
                }

                if ($inventoryObject["damages"] > 0) {

                    $this->recordDamages($inventory, $inventoryObject["damages"], $request->date, $production);
                }
            }






            if ((new AppController())->isApi($request)) {
                //API Response
                //                return response()->json(new SiteResource($site));
            } else {

                //Web Response
                return Redirect::back()->with("success", "New Production Report added!");
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
    }

    private function recordUsage(int $id, $quantity, $date, $production)
    {
        $material = Material::find($id);
        $cost = 0;
        $total = $quantity;
        if ($quantity > 0) {
            do {
                $batch = $material->batches()->where("balance", ">", 0)->orderBy("date", "asc")->first();
                $count = 0;
                if (is_object($batch)) {
                    //check if batch quantity is greater
                    if ($batch->balance >= $quantity) {
                        $count = $quantity;
                        $balance = $batch->balance - $quantity;
                    }
                    //this branch if batch balance is lower
                    else {
                        $count = $batch->balance;
                        $balance = 0;
                    }

                    // dd("Count: $count", "Quantity Remaining: $quantity", "Batch: $batch", "Balance: $balance");

                    //update the balance
                    $batch->update([
                        "balance" => $balance,
                    ]);
                    //update the balance
                    $q = $material->quantity - $count;
                    $material->update([
                        'quantity' => $q
                    ]);

                    //create a record of the transaction
                    Usage::create([
                        "date" => $date,
                        "quantity" => $count,
                        "cost" => $batch->price * $count,
                        "material_id" => $material->id,
                        "production_id" => $production->id,
                    ]);

                    //append cost
                    $cost += $batch->price * $count;
                } else {
                    return Redirect::back()->with("error", "{$material->name} is out of stock");
                }

                $quantity -= $count;
            } while ($quantity > 0);

            // dump("{$material->name} Cost: ". ($cost / $total));

            return $cost / $total;
        } else {
            return 0;
        }
    }

    private function recordDamages($inventory, $quantity, $date, $production)
    {
        if ($quantity > 0) {
            do {
                $batch = $inventory->batches()->where("balance", ">", 0)->orderBy("date", "asc")->first();
                $count = 0;
                if (is_object($batch)) {
                    //check if batch quantity is greater
                    if ($batch->balance >= $quantity) {
                        $count = $quantity;
                        $balance = $batch->balance - $quantity;
                    }
                    //this branch if batch balance is lower
                    else {
                        $count = $batch->balance;
                        $balance = 0;
                    }

                    // dd("Count: $count", "Quantity Remaining: $quantity", "Batch: $batch", "Balance: $balance");

                    //update the balance
                    $batch->update([
                        "balance" => $balance,
                    ]);
                    //update the balance
                    $q = $inventory->quantity - $count;
                    $inventory->update([
                        'quantity' => $q
                    ]);

                    //create a record of the transaction
                    Damage::create([
                        "date" => $date,
                        "batch_id" => $batch->id,
                        "inventory_id" => $inventory->id,
                        "quantity" => $count,
                        "production_id" => $production->id,
                        "cost" => $batch->price * $count,
                    ]);
                } else {
                    return Redirect::back()->with("error", "{$inventory->name} is out of stock");
                }

                $quantity -= $count;
            } while ($quantity > 0);

            return 1;
        } else {
            return 0;
        }
    }

    private function getCodeNumber()
    {
        $last = Production::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }

    private function convertToArray($arr)
    {
        return [
            "id" => $arr->id,
            "date" => floatval($arr->date),
            "quantity" => floatval($arr->quantity),
            "cost" => $arr->cost,
            "material" => $arr->material,
        ];
    }
}
