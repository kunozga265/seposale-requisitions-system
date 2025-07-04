<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\ProductionResource;
use App\Http\Resources\SiteResource;
use App\Http\Resources\StockResource;
use App\Models\AccountingAccount;
use App\Models\AccountingRecord;
use App\Models\Batch;
use App\Models\Damage;
use App\Models\Inventory;
use App\Models\Material;
use App\Models\Production;
use App\Models\Site;
use App\Models\MaterialsType;
use App\Models\Usage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

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
                    'siteAccounts' => $site->accounts,
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

    function show(Request $request, $code)
    {

        $production = Production::where("code", $code)->first();

        if (is_object($production)) {

            return Inertia::render("Production/Show", [
                "production" => new ProductionResource($production),
            ]);
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Production report not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Production report not found');
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
                $inventory = Inventory::find($inventoryObject["id"]);

                if ($inventoryObject["damages"] > 0) {
                    if ($inventory->stock() >= $inventoryObject["damages"]) {
                        continue;
                    } else {
                        return Redirect::back()->with("error", "{$inventory->name} is out of stock cannot record damages");
                    }
                }

                if ($inventoryObject["quantity"] > 0) {
                    if (!$inventory->checkAccounts()) {
                        return Redirect::back()->with("error", "Please link associated accounts for {$inventory->name} in accounting centre");
                    }
                }
            }


            if ($total_quantity == 0) {
                return Redirect::back()->with("error", "No materials produced!");
            }

            // dd("". $total_quantity);
            error_log('Some message here.');


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


            // $cogs_account = $material->cogsAccount;
            // $cogs_account_balance = $material->cogsAccount->balance;


            $index = 0;
            $cost = 0;
            $filteredMaterials = [];
            foreach ($request->materials as $materialObject) {
                $usage_cost = $this->recordUsage(
                    $materialObject["id"],
                    $materialObject["quantity"],
                    $request->date,
                    $production
                );

                if ($usage_cost > 0) {

                    $filteredMaterials[] = [
                        "id" => $materialObject["id"],
                        "description" => $materialObject["name"] . " - " . $materialObject["quantity"] . " " . $materialObject["units"] . "(s)",
                        "cost" => $usage_cost,

                    ];

                    // $usage_cost = $usage_cost * $materialObject["quantity"];

                    // $material = Material::find($materialObject["id"]);

                    // //record cogs
                    // $cogs_record = $cogs_account->records()->create([
                    //     "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    //     "reference" => strtoupper(""),
                    //     "date" => Carbon::now()->getTimestamp() + $index,
                    //     "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                    //     "description" => $material->name,
                    //     "amount" => $usage_cost,
                    //     "opening_balance" => $cogs_account_balance,
                    //     "closing_balance" => $cogs_account_balance + $usage_cost,
                    //     "type" => "DEBIT", // incrementing the account balance
                    //     "accounting_account_id" => $cogs_account->id,

                    // ]);
                    // $cogs_account_balance += $usage_cost;


                    // //update inventory account
                    // $inventory_record = $inventory_account->records()->create([
                    //     "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    //     "reference" => strtoupper(""),
                    //     "date" => Carbon::now()->getTimestamp() + $index,
                    //     "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                    //     "description" => $material->name,
                    //     "amount" => $usage_cost,
                    //     "opening_balance" => $inventory_account_balance,
                    //     "closing_balance" => $inventory_account_balance - $usage_cost,
                    //     "type" => "CREDIT", // decrementing the account balance
                    //     "accounting_account_id" => $inventory_account->id,
                    //     "accounting_record_id" => $cogs_record->id,
                    // ]);
                    // $inventory_account_balance -= $usage_cost;

                    // $cogs_record->update([
                    //     "accounting_record_id" => $inventory_record->id,
                    // ]);
                }

                $index++;
                $cost += $usage_cost;
            }

            // $cogs_account->update([
            //     "balance" => $cogs_account_balance
            // ]);

            // $inventory_account->update([
            //     "balance" => $inventory_account_balance
            // ]);

            //record labour and food costs
            $this->recordExpenses($site, $production, $request->labour, $request->food);

            //average cost
            $average_cost = ($cost) / $total_quantity;

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


            $material = Material::first();
            $material_inventory_account = $material->inventoryAccount;
            $material_inventory_account_balance = $material->inventoryAccount->balance;

            //Record materials produced
            foreach ($request->inventories as $inventoryObject) {
                $inventory = Inventory::find($inventoryObject["id"]);
                $inventory_account = $inventory->inventoryAccount;
                if ($inventory_account != null) {
                    $inventory_account_balance = $inventory->inventoryAccount->balance;
                }

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
                        "accounting_balance" => $inventoryObject["quantity"],
                        "ready_date" =>  $inventoryObject["date"],
                        "photo" => null,
                        "comments" => "",
                        "inventory_id" => $inventory->id,
                        "production_id" => $production->id,
                        "user_id" => Auth::id(),
                    ]);

                    //record accounts
                    $amount = $average_cost * $inventoryObject["quantity"];
                    $inventory_record = $inventory_account->records()->create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp() + $index,
                        "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                        "description" => "{$inventory->name} - " . $inventory->formattedUnits($inventoryObject["quantity"]),
                        "amount" => $amount,
                        "opening_balance" => $inventory_account_balance,
                        "closing_balance" => $inventory_account_balance + $amount,
                        "type" => "DEBIT", // incrementing the account balance
                        "accounting_account_id" => $inventory_account->id,
                        "production_id" => $production->id,

                    ]);
                    $inventory_account_balance += $amount;


                    // //update materials accounts
                    foreach ($filteredMaterials as $material_object) {
                        $inventory_record = $material_inventory_account->records()->create([
                            "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                            "reference" => strtoupper(""),
                            "date" => Carbon::now()->getTimestamp() + $index,
                            "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                            "description" => $material_object["description"],
                            "amount" => $material_object["cost"],
                            "opening_balance" => $material_inventory_account_balance,
                            "closing_balance" => $material_inventory_account_balance - $material_object["cost"],
                            "type" => "CREDIT", // decrementing the account balance
                            "accounting_account_id" => $material_inventory_account->id,
                            "accounting_record_id" => $inventory_record->id,
                            "production_id" => $production->id,
                        ]);
                        $material_inventory_account_balance -= $material_object["cost"];
                    }

                    $inventory_record->update([
                        // "accounting_record_id" => $inventory_record->id,
                    ]);

                    $material_inventory_account->update([
                        "balance" => $material_inventory_account_balance
                    ]);
                }

                $index++;

                if ($inventoryObject["damages"] > 0) {
                    $damages_cost = $this->recordDamages($inventory, $inventoryObject["damages"], $request->date, $production);

                    $operating_expenses_account = AccountingAccount::where("code", 6050)->first();
                    $operating_expenses_record = $operating_expenses_account->records()->create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp() + $index,
                        "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                        "description" => "{$inventory->name} Damages - " . $inventory->formattedUnits($inventoryObject["damages"]),
                        "amount" => $damages_cost,
                        "opening_balance" => $operating_expenses_account->balance,
                        "closing_balance" => $operating_expenses_account->balance + $damages_cost,
                        "type" => "DEBIT", // incrementing the account balance
                        "accounting_account_id" => $inventory_account->id,
                        "production_id" => $production->id,
                    ]);
                    $operating_expenses_account->update([
                        "balance" => $operating_expenses_account->balance + $damages_cost
                    ]);

                    //decrease inventory
                    $inventory_record = $inventory_account->records()->create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp() + $index,
                        "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                        "description" => "{$inventory->name} Damages - " . $inventory->formattedUnits($inventoryObject["damages"]),
                        "amount" => $damages_cost,
                        "opening_balance" => $inventory_account_balance,
                        "closing_balance" => $inventory_account_balance - $damages_cost,
                        "type" => "CREDIT", // incrementing the account balance
                        "accounting_account_id" => $inventory_account->id,
                        "accounting_record_id" => $operating_expenses_record->id,
                        "production_id" => $production->id,

                    ]);
                    $inventory_account_balance -= $damages_cost;

                    $operating_expenses_record->update([
                        "accounting_record_id" => $inventory_record->id,
                    ]);
                }

                if ($inventory_account != null) {
                    $inventory_account->update([
                        "balance" => $inventory_account_balance
                    ]);
                }
                $index++;
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
                        "accounting_balance" => $balance,
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
                        "batch_id" => $batch->id,
                    ]);

                    //append cost
                    $cost += $batch->price * $count;
                } else {
                    return Redirect::back()->with("error", "{$material->name} is out of stock");
                }

                $quantity -= $count;
            } while ($quantity > 0);

            // dump("{$material->name} Cost: ". ($cost / $total));

            // return $cost / $total;
            return $cost;
        } else {
            return 0;
        }
    }

    private function recordDamages($inventory, $quantity, $date, $production)
    {
        $cost = 0;
        if ($quantity > 0) {
            do {
                $batch = $inventory->batches()->where("accounting_balance", ">", 0)->orderBy("date", "asc")->first();
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
                    $q = $inventory->available_stock - $count;
                    $inventory->update([
                        'available_stock' => $q
                    ]);

                    $damages_cost = $batch->price * $count;
                    //create a record of the transaction
                    Damage::create([
                        "date" => $date,
                        "batch_id" => $batch->id,
                        "inventory_id" => $inventory->id,
                        "quantity" => $count,
                        "production_id" => $production->id,
                        "cost" => $damages_cost,
                    ]);
                } else {
                    return Redirect::back()->with("error", "{$inventory->name} is out of stock");
                }
                $cost += $damages_cost;
                $quantity -= $count;
            } while ($quantity > 0);
        }
        return $cost;
    }

    private function recordExpenses(Site $site, Production $production, $labour, $food)
    {
        $direct_costs_account_balance = $site->directCostsAccount()->balance;
        $wallet_account_balance = $site->walletAccount()->balance;
        $index = 0;

        //record labour costs
        if ($labour > 0) {
            //record direct costs
            $direct_costs_record = $site->directCostsAccount()->records()->create([
                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                "reference" => strtoupper(""),
                "date" => Carbon::now()->getTimestamp() + $index,
                "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                "description" => "Labour cost",
                "amount" => $labour,
                "opening_balance" => $direct_costs_account_balance,
                "closing_balance" => $direct_costs_account_balance + $labour,
                "type" => "DEBIT", // incrementing the account balance
                "accounting_account_id" => $site->directCostsAccount()->id,
                "production_id" => $production->id,

            ]);
            $direct_costs_account_balance += $labour;


            //update wallet account
            $wallet_record = $site->walletAccount()->records()->create([
                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                "reference" => strtoupper(""),
                "date" => Carbon::now()->getTimestamp() + $index,
                "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                "description" => "Labour Cost",
                "amount" => $labour,
                "opening_balance" => $wallet_account_balance,
                "closing_balance" => $wallet_account_balance - $labour,
                "type" => "CREDIT", // decrementing the account balance
                "accounting_account_id" => $site->walletAccount()->id,
                "accounting_record_id" => $direct_costs_record->id,
                "production_id" => $production->id,
            ]);
            $wallet_account_balance -= $labour;

            $direct_costs_record->update([
                "accounting_record_id" => $wallet_record->id,
            ]);
        }

        $index++;

        //record food costs
        if ($food > 0) {
            //record direct costs
            $direct_costs_record = $site->directCostsAccount()->records()->create([
                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                "reference" => strtoupper(""),
                "date" => Carbon::now()->getTimestamp() + $index,
                "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                "description" => "Food cost",
                "amount" => $food,
                "opening_balance" => $direct_costs_account_balance,
                "closing_balance" => $direct_costs_account_balance + $food,
                "type" => "DEBIT", // incrementing the account balance
                "accounting_account_id" => $site->directCostsAccount()->id,
                "production_id" => $production->id,

            ]);
            $direct_costs_account_balance += $food;


            //update wallet account
            $wallet_record = $site->walletAccount()->records()->create([
                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                "reference" => strtoupper(""),
                "date" => Carbon::now()->getTimestamp() + $index,
                "name" => "Production Report #" . (new AppController())->getZeroedNumber($production->code),
                "description" => "Food Cost",
                "amount" => $food,
                "opening_balance" => $wallet_account_balance,
                "closing_balance" => $wallet_account_balance - $food,
                "type" => "CREDIT", // decrementing the account balance
                "accounting_account_id" => $site->walletAccount()->id,
                "accounting_record_id" => $direct_costs_record->id,
                "production_id" => $production->id,
            ]);
            $wallet_account_balance -= $food;

            $direct_costs_record->update([
                "accounting_record_id" => $wallet_record->id,
            ]);
        }

        $site->walletAccount()->update([
            "balance" => $wallet_account_balance
        ]);
        $site->directCostsAccount()->update([
            "balance" => $direct_costs_account_balance
        ]);
    }
    public function destroy(Request $request, $code)
    {
        $production = Production::where("code", $code)->first();

        if (is_object($production)) {
            //check if any sale where made with this batch
            foreach ($production->batches as $batch) {
                if ($batch->siteSales->count() > 0) {
                    return Redirect::back()->with("error", "Error when deleting production report for {$batch->inventory->name}. Sales have already linked to products produced from this report. Make all necessary corrections on the next report.");
                }
            }

            //delete new batches
            foreach ($production->batches as $batch) {
                if ($batch->siteSales->count() > 0) {
                    $quantity = 0;
                    foreach ($batch->siteSales as $sale) {
                        foreach ($sale->products as $summary) {
                            if ($summary->inventory->id == $batch->inventory->id) {
                                $quantity += $summary->quantity;
                            }
                        }
                    }

                    $replacement_batch = $batch->inventory->batches()
                        ->where("balance", ">=", $quantity)
                        ->where("id", "!=", $batch->id)
                        ->orderBy("date", "asc")
                        ->first();

                    if (is_object($replacement_batch)) {
                        $balance = $replacement_batch->balance - $quantity;
                        //update the balance
                        $replacement_batch->update([
                            "balance" => $balance,
                        ]);

                        foreach ($batch->siteSales as $sale) {
                            $sale->batches()->detach($batch);
                            $sale->batches()->attach($replacement_batch);
                        }

                        $quantity = $batch->inventory->available_stock - $batch->quantity;
                        $batch->inventory->update([
                            "available_stock" => $quantity
                        ]);

                        $batch->delete();
                    } else {
                        return Redirect::back()->with("error", "Error when deleting batch for {$batch->inventory->name}. There is no other replacement batch found to link to existing sales.");
                    }
                } else {
                    //it can be easily deleted, has no connections anywhere
                    $quantity = $batch->inventory->available_stock - $batch->quantity;
                    $batch->inventory->update([
                        "available_stock" => $quantity
                    ]);
                    $batch->delete();
                }
            }

            //delete usages
            foreach ($production->usages as $usage) {
                //material balance
                $quantity = $usage->material->quantity + $usage->quantity;
                $usage->material->update([
                    "quantity" => $quantity
                ]);

                //batch linked
                if ($usage->batch != null) {
                    $batch = $usage->batch;
                } else {
                    $batch = $usage->material->batches()->orderBy("date", "desc")->first();
                }

                $balance = $batch->balance + $usage->quantity;
                $batch->update([
                    "balance" => $balance
                ]);
                $usage->delete();
            }

            //delete damages
            foreach ($production->damages as $damage) {
                //inventory balance
                $quantity = $damage->inventory->available_stock + $damage->quantity;
                $damage->inventory->update([
                    "available_stock" => $quantity
                ]);

                //batch linked
                $balance = $damage->batch->balance + $damage->quantity;
                $damage->batch->update([
                    "balance" => $balance
                ]);
                $damage->delete();
            }

            //reverse transactions
           (new AccountingRecordController())->reverseTransactions($production->records, $production->id);

            //delete production
            $production->delete();

            return Redirect::route("production.index", ["code" => $production->site->code])->with("success", "Successfully deleted the production report");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Production not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Production report not found');
            }
        }
    }

    private function getCodeNumber()
    {
        $last = Production::orderBy("code", "desc")->withTrashed()->first();
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

    public function print(Request $request, $code)
    {
        //find out if the request is valid
        $production = Production::where("code", $code)->first();

        if (is_object($production)) {

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $filename = "PRODUCTION#" . (new AppController())->getZeroedNumber($production->code) . " - " .  date('Ymd', $production->date);

            $date = Carbon::createFromTimestamp($production->date, 'Africa/Lusaka')->format('F j, Y');

            $pdf = PDF::loadView('production', [
                'code'              => (new AppController())->getZeroedNumber($production->code),
                'date'              => $date,
                'production'        => $production,
            ]);

            return $pdf->download("$filename.pdf");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Production not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Production report not found');
            }
        }
    }
}
