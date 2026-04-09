<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\SiteSaleResource;
use App\Models\Account;
use App\Models\AccountingAccount;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Inventory;
use App\Models\InventorySummary;
use App\Models\PaymentMethod;
use App\Models\Site;
use App\Models\SiteSale;
use App\Models\SiteSaleSummary;
use App\Models\Summary;
use App\Models\AccountingRecord;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SiteSaleController extends Controller
{
    private $paginate = 50;

    public function index(Request $request, $code, $section)
    {

        $site = Site::where("code", $code)->first();
        if (!is_object($site)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }

        $filter = strtolower($request->query("filter"));
        if ($filter == "unpaid") {
            $sales = SiteSale::where("status", 0)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "unpaid";
        } else if ($filter == "partially-paid") {
            $sales = SiteSale::where("status", 1)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "partially-paid";
        } else if ($filter == "fully-paid") {
            $sales = SiteSale::where("status", 2)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "fully-paid";
        } else if ($filter == "closed") {
            $sales = SiteSale::where("status", 3)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "closed";
        } else if ($filter == "discarded") {
            $sales = SiteSale::onlyTrashed()->paginate($this->paginate);
            $headline = "discarded";
        } else {
            $sales = SiteSale::orderBy("date", "desc")->paginate($this->paginate);
            $headline = "all";
        }

        $allSales = SiteSale::orderBy("date", "desc")->paginate($this->paginate);

        $unsorted = SiteSale::orderBy("date", "desc")->get();
        $sorted = [];

        if (!$unsorted->isEmpty()) {
            $currentMonth = date('F', $unsorted[0]->date);
            $currentYear = date('Y', $unsorted[0]->date);

            $item = 0;
            $index = 0;
            foreach ($unsorted as $sale) {

                if ($item == 0) {
                    $sum = 0;
                    $unpaid = 0;
                    $profit = 0;
                    $costs = 0;
                    foreach ($sale->products as $summary) {
                        if ($summary->getCollectionStatus() > 0 || $summary->getPaymentStatus() > 0) {
                            $sum += $summary->paid();
                            $unpaid += $summary->pendingPayments();
                            $profit += $summary->profit() > 0 ? $summary->profit() : 0;
                            $costs += $summary->collectionCosts();
                        }
                    }
                    $sorted[0] = [
                        'month' => $currentMonth,
                        'year' => $currentYear,
                        'total' => $sum,
                        'unpaid' => $unpaid,
                        'profit' => $profit,
                        'costs' => $costs
                    ];
                } else {
                    $month = date('F', $unsorted[$item]->date);
                    $year = date('Y', $unsorted[$item]->date);

                    if ($currentMonth === $month && $currentYear === $year) {
                        $sum = $sorted[$index]['total'];
                        $profit = $sorted[$index]['profit'];
                        $unpaid = $sorted[$index]['unpaid'];
                        $costs = $sorted[$index]['costs'];
                        foreach ($sale->products as $summary) {
                            if ($summary->getCollectionStatus() > 0 || $summary->getPaymentStatus() > 0) {
                                $sum += $summary->amount;
                                $profit += $summary->profit() > 0 ? $summary->profit() : 0;
                                $unpaid += $summary->pendingPayments();
                                $costs += $summary->collectionCosts();
                            }
                        }
                        $sorted[$index]['total'] = $sum;
                        $sorted[$index]['unpaid'] = $unpaid;
                        $sorted[$index]['profit'] = $profit;
                        $sorted[$index]['costs'] = $costs;
                    } else {
                        $index += 1;
                        $currentMonth = date('F', $unsorted[$item]->date);
                        $currentYear = date('Y', $unsorted[$item]->date);

                        $sum = 0;
                        $unpaid = 0;
                        $profit = 0;
                        $costs = 0;

                        foreach ($sale->products as $summary) {
                            if ($summary->getCollectionStatus() > 0 || $summary->getPaymentStatus() > 0) {
                                $sum += $summary->paid();
                                $unpaid += $summary->pendingPayments();
                                $profit += $summary->profit() > 0 ? $summary->profit() : 0;
                                $costs += $summary->collectionCosts();
                            }
                        }

                        $sorted[$index] = [
                            'month' => $currentMonth,
                            'year' => $currentYear,
                            'total' => $sum,
                            'unpaid' => $unpaid,
                            'profit' => $profit,
                            'costs' => $costs
                        ];
                    }
                }
                $item += 1;
            }
        }

        $chartData = [];
        $currentYear = "";
        $index = -1;
        foreach ($sorted as $item) {
            $year = $item["year"];
            if ($currentYear != $year) {
                $index++;
                $currentYear = $year;
                $chartData[$index] = [
                    "year" => $currentYear,
                    "data" => [$item]
                ];
            } else {
                $chartData[$index]["data"][] = $item;
            }
        }

        for ($i = 0; $i < count($chartData); $i++) {
            $chartData[$i]["data"] = array_reverse($chartData[$i]["data"]);
        }

        // dd($chartData);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(SiteSaleResource::collection($sales));
        else {
            //Web Response
            return Inertia::render('SiteSales/Index', [
                'sales' => SiteSaleResource::collection($section == "block" ? $sales : $allSales),
                'headline' => $headline,
                'section' => $section,
                'chartData' => $chartData,
                "site" => $site,
            ]);
        }
    }

    public function show(Request $request, $code, $id)
    {
        $site = Site::where("code", $code)->first();
        if (is_object($site)) {
            //find out if the request is valid
            $sale = SiteSale::withTrashed()->find($id);
            $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
            $accounts = AccountingAccount::where('special_type', 'WALLET')
                ->orderBy('name', 'asc')
                ->get();

            if (is_object($sale)) {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(new SiteSaleResource($sale));
                } else {
                    //Web Response
                    return Inertia::render('SiteSales/Show', [
                        'sale' => new SiteSaleResource($sale),
                        'paymentMethods' => $payment_methods,
                        'accounts' => $accounts,
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
            $types = ClientType::orderBy("name", "asc")->get();
            $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
            return Inertia::render('SiteSales/Create', [
                "products" => InventoryResource::collection($products),
                "clients" => ClientResource::collection($clients),
                "clientTypes" => $types,
                'paymentMethods' => $payment_methods,
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

        //        //Check if materials are not out of stock
        //        if ($out_of_stock) {
        //            if ((new AppController())->isApi($request)) {
        //                //API Response
        //                return response()->json(['message' => $error_message], 404);
        //            } else {
        //                //Web Response
        //                return Redirect::back()->with('error', $error_message);
        //            }
        //        }



        //Validate all the important attributes
        $request->validate([
            'products' => ['required'],
            'total' => ['required'],
            'payment_method_id' => ['required'],
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
                'client_type_id' => ['required'],
                'phoneNumber' => ['required'],
            ]);

            if ($request->client_type_id == 0) {
                $request->validate([
                    'client_type' => ['required'],
                ]);
                $client_type_id = ClientType::create([
                    "name" => ucwords($request->client_type)
                ])->id;
            } else {
                $client_type_id = $request->client_type_id;
            }

            if (Client::where("phone_number", $request->phoneNumber)->exists()) {
                $existing_client = Client::where("phone_number", $request->phoneNumber)->first();
                if ((new AppController())->isApi($request))
                    //API Response
                    return response()->json(["message" => "Client with that phone number exists: {$existing_client->getName()}"], 400);
                else {
                    //Web Response
                    return Redirect::back()->with('error', "Client with that phone number exists: {$existing_client->getName()}");
                }
            }


            $client = Client::create([
                'serial' => (new AppController())->generateUniqueCode("CLIENT"),
                'name' => ucwords($request->name),
                'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
                'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
                'email' => $request->email,
                'address' => $request->address,
                'organisation' => $request->organisation,
                'alias' => $request->alias,
                'client_type_id' => $client_type_id,
            ]);
        }
        $sale = Cache::lock($user->id . ':sites.sales:store', 10)->get(function () use ($site, $user, $request, $client) {

            $inventorySummary = (new InventorySummaryController())->getSummary($site->id);

            $sale = SiteSale::create([
                'code' => $this->getCodeNumber(),
                'serial' => (new AppController())->generateUniqueCode("SITESALE"),
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
                'payment_method_id' => $request->payment_method_id,
                'reference' => $request->reference,
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
                $cost = 0;
                //update batches
                $cost += $this->updateBatches($inventory, $product["quantity"], $sale);

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
                    "cost" => $cost,
                ]);
            }

            return $sale;
        });

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new SiteSaleResource($sale), 201);
        else {
            //Web Response
            if ($out_of_stock) {
                return Redirect::route('sites.overview', ['code' => $site->code])->with('warning', 'Sales created. But sold unavailable stock!');
            } else {
                return Redirect::route('sites.overview', ['code' => $site->code])->with('success', 'Sale created!');
            }
        }
    }
    public function storeFromSale(Request $request, Summary $summary, Inventory $inventory)
    {
        $out_of_stock = $summary->quantity > $inventory->available_stock;

        $request->validate([
            "amount" => "required"
        ]);

        $amount = $request->amount;
        $paid = $summary->amount - $summary->balance;

        if ($amount == $summary->amount) {
            $quantity = $summary->quantity;
            $balance = $summary->balance;
        } else {
            $quantity = round($amount / $summary->cost());
            $balance = $amount - $paid;
        }


        $siteSaleSummary = Cache::lock(Auth::id() . ':sites.sales:store-from-sale', 10)->get(function () use ($summary, $inventory, $quantity, $amount, $balance) {

            $inventory_summary = (new InventorySummaryController())->getSummary($inventory->site->id);

            $sale = SiteSale::create([
                'code' => $this->getCodeNumber(),
                'serial' => (new AppController())->generateUniqueCode("SITESALE"),
                'status' => 0,
                'client_id' => $summary->sale->client->id,
                'total' => $amount,
                'balance' => $balance ?? 0,
                'date' => $summary->sale->date,
                'editable' => false,
                //Generated by
                'user_id' => Auth::id(),
                'site_id' => $inventory->site->id,
                'inventory_summary_id' => $inventory_summary->id,
                'payment_method_id' => 0,
                'reference' => "",
            ]);

            $cost = 0;
            //update batches
            $cost += $this->updateBatches($inventory, $quantity, $sale);

            $available_stock = $inventory->available_stock - $quantity;
            $uncollected_stock = $inventory->uncollected_stock + $quantity;

            $inventory->update([
                'available_stock' => $available_stock,
                'uncollected_stock' => $uncollected_stock,
            ]);

            $siteSaleSummary = SiteSaleSummary::create([
                "inventory_id" => $inventory->id,
                "site_sale_id" => $sale->id,
                "quantity" => $quantity,
                "amount" => $amount,
                "balance" => $balance ?? 0,
                "collected" => 0,
                "cost" => $cost,
            ]);

            //attach financials
            foreach ($summary->receiptSummaries as $receiptSummary) {

                $receiptSummary->update([
                    "site_sale_summary_id" => $siteSaleSummary->id,
                ]);
                $receiptSummary->receipt->update([
                    "site_sale_id" => $sale->id,
                ]);
            }

            return $siteSaleSummary;
        });

        return $siteSaleSummary;

        // if ((new AppController())->isApi($request))
        //     //API Response
        //     return response()->json(new SiteSaleResource($sale), 201);
        // else {
        //     //Web Response
        //     if ($out_of_stock) {
        //         return Redirect::route('sites.overview', ['code' => $inventory->site->code])->with('warning', 'Sales created. But sold unavailable stock!');
        //     } else {
        //         return Redirect::route('sites.overview', ['code' => $inventory->site->code])->with('success', 'Sale created!');
        //     }
        // }
    }

    private function updateBatches(Inventory $inventory, $quantity, SiteSale $sale)
    {
        $cost = 0;
        $total = $quantity;
        if ($quantity > 0) {
            do {
                $batch = $inventory->batches()->where("balance", ">", 0)->orderBy("date", "asc")->first();
                $count = 0;
                //get the last batch
                if (!is_object($batch)) {
                    $batch = $inventory->batches()->orderBy("date", "desc")->first();
                }

                //if batch is zero, there is nothing to update just return the estimated cost
                if (!is_object($batch)) {
                    return $cost + $inventory->cost * $quantity;
                } else if ($batch->balance == 0) {
                    return $cost + $batch->price * $quantity;
                }

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

                //update the balance
                $batch->update([
                    "balance" => $balance,
                ]);

                //attach batch to sale
                $sale->batches()->attach($batch);

                //append cost
                $cost += $batch->price * $count;

                $quantity -= $count;
            } while ($quantity > 0);

            return $cost;
        } else {
            return 0;
        }
    }


    private function getCodeNumber()
    {
        $last = SiteSale::withTrashed()->orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }

    public function destroy(Request $request, $id)
    {
        //find out if the request is valid
        $sale = SiteSale::find($id);

        if (is_object($sale)) {

            $site_code = $sale->site->code;

            //detach receipts
            foreach ($sale->receipts as $receipt) {
                if ($receipt->sale != null) {
                    $receipt->update([
                        'site_sale_id' => null
                    ]);
                } else {
                    (new ReceiptController())->deleteReceipt($receipt);
                }
            }

            //detach products
            foreach ($sale->products as $product) {

                foreach ($product->collections as $collection) {
                    (new CollectionController())->deleteCollection($collection);
                }

                if ($product->summary != null) {
                    $product->summary->update([
                        "status" => 0,
                        "site_sale_summary_id" => null
                    ]);
                }

                $product->delete();
            }

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "Sale #{$sale->code} has been deleted.",
                "site_sale_id" => $sale->id,
            ]);

            $sale->delete();

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => 'Sale has been deleted']);
            } else {
                //Web Response
                return Redirect::route('sites.overview', ['code' => $site_code])->with('success', 'Sale has been deleted');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "sale not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Sale not found');
            }
        }
    }
}
