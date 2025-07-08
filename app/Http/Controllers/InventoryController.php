<?php

namespace App\Http\Controllers;

use App\Http\Resources\BatchResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\DamageResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReceiptSummaryResource;
use App\Http\Resources\SiteSaleSummaryResource;
use App\Models\Batch;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptSummary;
use App\Models\Site;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function show(Request $request, $code, $id)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            //
            $inventory = $site->inventories()->where("id", $id)->first();

            if (is_object($inventory)) {
                $section  = strtolower($request->query("section"));

                $sales = [];
                $collections = [];
                $pending_collections = [];
                $batches = [];
                $damages = [];
                $pending_batches = [];
                $filtered_receipts = [];

                switch ($section) {
                    case "sales":
                        //get sales
                        $summaries = $inventory->summaries()->latest()->get();
                        foreach ($summaries as $receipt_summary) {
                            $sales[] = [
                                "id" => $receipt_summary->sale->id,
                                "code" => (new AppController())->getZeroedNumber($receipt_summary->sale->code),
                                "client" => $receipt_summary->sale->client,
                                "date" => $receipt_summary->sale->date,
                                "products" => [
                                    new SiteSaleSummaryResource($receipt_summary)
                                ],
                            ];
                        }
                        break;
                    case "collections":
                        $collections = $inventory->collections;
                        break;
                    case "batches":
                        //get batches
                        $batches = $inventory->batches()->orderBy("date", "desc")->get();
                        break;
                    case "damages":
                        //get damages
                        $damages = $inventory->damages()->orderBy("date", "desc")->get();
                        break;
                    default:
                        $section = "overview";
                        $receipt_summaries = ReceiptSummary::where("site_sale_summary_id", "!=", null)->orderBy("created_at", "asc")->get();
                        foreach ($receipt_summaries as $receipt_summary) {

                            if ($receipt_summary->siteSaleSummary->inventory->id == $inventory->id) {
                                $filtered_receipts[] = $receipt_summary;
                            }
                        }

                        $now = Carbon::now();
                        $pending_batches = $inventory->batches()
                            ->where("ready_date", "!=", null)
                            ->where("ready_date", ">=", $now->getTimestamp())
                            ->orderBy("ready_date", "asc")
                            ->get();

                            $pending_collections = $inventory->pendingCollections();
                }

                //get products
                $products = Product::orderBy("name", "asc")->get();



                return Inertia::render('Inventories/Show', [
                    "site" => $site,
                    "section" => $section,
                    "inventory" => new InventoryResource($inventory),
                    "sales" => $sales,
                    "receipts" => ReceiptSummaryResource::collection($filtered_receipts),
                    "collections" => CollectionResource::collection($collections),
                    "batches" => BatchResource::collection($batches),
                    "pendingBatches" => BatchResource::collection($pending_batches),
                    "damages" => DamageResource::collection($damages),
                    "products" => ProductResource::collection($products),
                    "pendingCollections" => $pending_collections,
                ]);
            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Daily Report not found"], 404);
                } else {
                    //Web Response
                    return Redirect::route('dashboard')->with('error', 'Daily Report not found');
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

    public function store(Request $request, string $code)
    {

        $site = Site::where("code", $code)->first();

        if (is_object($site)) {

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
                'units' => ['required'],
                'threshold' => ['required', "numeric", "gt:0"],
                'cost' => ['required', "numeric", "gt:0"],
                'product_id' => ['required'],
            ]);

            $inventory = Inventory::create([
                "name" => $request->name,
                "units" => $request->units,
                "cost" => $request->cost,
                "threshold" => $request->threshold,
                "producible" => $request->producible,
                "product_id" => $request->product_id,
                "site_id" => $site->id,
                "available_stock" => 0,
                "uncollected_stock" => 0,
            ]);

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "New Inventory Added",
                "inventory_id" => $inventory->id,
                "contents" => json_encode([])
            ]);


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new InventoryResource($inventory), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', "{$inventory->name}: Inventory added!");
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Resource not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Resource not found');
            }
        }
    }

    public function edit(Request $request)
    {

        $inventory = Inventory::find($request->inventory_id);

        if (is_object($inventory)) {

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
                'units' => ['required'],
                'threshold' => ['required', "numeric", "gt:0"],
                'cost' => ['required', "numeric", "gt:0"],
                'product_id' => ['required'],
            ]);

            $available_stock = 0;
            foreach ($request->batches as $batch_object) {
                $batch = Batch::find($batch_object["id"]);
                $batch->update([
                    "balance" => $batch_object["balance"],
                ]);

                $available_stock += $batch_object["balance"];
            }

            $inventory->update([
                "name" => $request->name,
                "units" => $request->units,
                "cost" => $request->cost,
                "threshold" => $request->threshold,
                "producible" => $request->producible,
                'available_stock' => $available_stock,
                'uncollected_stock' => $request->uncollected_stock,
                'product_id' => $request->product_id,
            ]);

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "Inventory {$inventory->name} updated",
                "inventory_id" => $inventory->id,
                "contents" => json_encode([])
            ]);

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new InventoryResource($inventory), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', "{$inventory->name}: Inventories updated!");
            }
        } else {
            return Redirect::back()->with('error', 'Resource not found');
        }
    }

    public function update(Request $request)
    {

        $inventory = Inventory::find($request->inventory_id);

        if (is_object($inventory)) {

            //Validate all the important attributes
            $request->validate([
                'total' => ['required', "numeric", "gt:0"],
                'quantity' => ['required', "numeric", "gt:0"],
                'date' => ['required'],
            ]);

            $availableStock = $inventory->available_stock + $request->quantity;

            //if there is still available stock just append quantity
            if ($inventory->available_stock  >= 0) {
                $batch_balance = $request->quantity;
            } else {
                //if adding new stock results in a SURPLUS of stock, set batch quantity to surplus
                if ($availableStock >= 0) {
                    $batch_balance = $availableStock;
                }
                //if adding new stock results in a DEFICIT of stock, set batch quantity to 0
                else {
                    $batch_balance = 0;
                }
            }

            $inventory->update([
                'available_stock' => $availableStock
            ]);

            Batch::create([
                "date" => $request->date,
                "ready_date" => $request->date,
                "price" =>  $request->total / $request->quantity,
                "quantity" => $request->quantity,
                "accounting_balance" => $request->quantity,
                "balance" =>  $batch_balance,
                "photo" => $request->photo ?? null,
                "comments" => $request->comments,
                "inventory_id" => $inventory->id,
                "user_id" => Auth::id(),
            ]);


            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "New Stock! Added {$request->quantity} to {$inventory->name}",
                "inventory_id" => $inventory->id,
                "contents" => json_encode([
                    "date" => $request->date,
                    "quantity" => $request->quantity,
                    "comments" => $request->comments,
                    "photo" => $request->photo,
                ])
            ]);

            //Run notifications
            //        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


            //        $report = (new ReportController())->getCurrentReport();
            //        $report->requestForms()->attach($requestForm);

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new InventoryResource($inventory), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', "{$inventory->name}: Inventories updated!");
            }
        } else {
            return Redirect::back()->with('error', 'Resource not found');
        }
    }
}
