<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\SiteSaleResource;
use App\Models\Client;
use App\Models\Inventory;
use App\Models\InventorySummary;
use App\Models\PaymentMethod;
use App\Models\Site;
use App\Models\SiteSale;
use App\Models\SiteSaleSummary;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SiteSaleController extends Controller
{

    public function show(Request $request, $code, $id)
    {
        $site = Site::where("code", $code)->first();
        if (is_object($site)) {
            //find out if the request is valid
            $sale = SiteSale::withTrashed()->find($id);
            $payment_methods = PaymentMethod::orderBy("name", "asc")->get();

            if (is_object($sale)) {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(new SiteSaleResource($sale));
                } else {
                    //Web Response
                    return Inertia::render('SiteSales/Show', [
                        'sale' => new SiteSaleResource($sale),
                        'paymentMethods' => $payment_methods,
                        "site" => $site,
                    ]);
                }
            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Sale not found"], 404);
                } else {
                    //Web Response
                    return Redirect::route('dashboard')->with('error', 'Sale not found');
                }
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

    public function create(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();
        if (is_object($site)) {
            $products = $site->inventories()->orderBy("name", 'asc')->get();
            $clients = Client::orderBy("name", 'asc')->get();
            return Inertia::render('SiteSales/Create', [
                "products" => InventoryResource::collection($products),
                "clients" => ClientResource::collection($clients),
                "site" => $site,
            ]);
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


    public function store(Request $request, $code)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);
        $site = Site::where("code", $code)->first();

        //Check if site details are valid
        if (!is_object($site)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
        $out_of_stock = false;
        $error_message = "";
        foreach ($request->products as $product) {
            $inventory = Inventory::find($product["id"]);

            if ($product["quantity"] > $inventory->available_stock) {
                $out_of_stock = true;
                $error_message = "{$inventory->name}: Only {$inventory->available_stock} {$inventory->units}(s) in stock.";
                break;
            }
        }

        //Check if materials are not out of stock
        if ($out_of_stock) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => $error_message], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', $error_message);
            }
        }

        $sale = Cache::lock($user->id . ':sites.sales:store', 10)->get(function () use ($site, $user, $request) {

            //Validate all the important attributes
            $request->validate([
                'products' => ['required'],
                'total' => ['required'],
            ]);

            //get client info
            if (isset($request->client_id)) {
                $request->validate([
                    'client_id' => ['required'],
                ]);

                $client = Client::find($request->client_id);
                if (!is_object($client)) {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "Client not found"], 404);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'Client not found');
                    }
                }
            } else {
                $request->validate([
                    'name' => ['required'],
                ]);

                $client = Client::create([
                    'serial' =>  (new AppController())->generateUniqueCode("CLIENT"),
                    'name' => $request->name,
                    'phone_number' => $request->phoneNumber,
                    'email' => $request->email,
                    'address' => $request->address,
                ]);
            }

            $inventorySummary = (new InventorySummaryController())->getSummary($site->id);

            $sale = SiteSale::create([
                'code' => $this->getCodeNumber(),
                'serial' =>  (new AppController())->generateUniqueCode("SITESALE"),
                'status' => 0,
                'client_id' => $client->id,
                'total' => $request->total,
                'balance' => $request->total,
                'date' => isset($request->date) ? $request->date : Carbon::now()->getTimestamp(),
                'editable' => true,
                //Generated by
                'user_id' => $user->id,
                'site_id' => $site->id,
                'inventory_summary_id' => $inventorySummary->id,
            ]);

//            //Logging
//            SystemLog::create([
//                "user_id" => Auth::id(),
//                "message" => "{$site->name} Sale #{$sale->code} created for {$client->name}. Total amount is {$sale
//        ->total}",
//                "sale_id" => $sale->id,
//            ]);

            //attach products
            foreach ($request->products as $product) {
                $inventory = Inventory::find($product["id"]);
                $available_stock = $inventory->available_stock - $product["quantity"];
                $uncollected_stock = $inventory->uncollected_stock + $product["quantity"];

                $inventory->update([
                    'available_stock' => $available_stock,
                    'uncollected_stock' => $uncollected_stock,
                ]);

                $summary = SiteSaleSummary::create([
                    "inventory_id" => $product["id"],
                    "site_sale_id" => $sale->id,
                    "quantity" => $product["quantity"],
                    "amount" => $product["totalCost"],
                    "balance" => $product["totalCost"],
                    "collected" => 0,
                ]);
            }

            return $sale;


        });

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new SiteSaleResource($sale), 201);
        else {
            //Web Response
            return Redirect::route('sites.overview', ['code' => $site->code])->with('success', 'Sale created!');
        }

    }


    private function getCodeNumber()
    {
        $last = SiteSale::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }
}
