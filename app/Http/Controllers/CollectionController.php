<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Http\Resources\SiteResource;
use App\Http\Resources\SiteSaleSummaryResource;
use App\Models\AccountingRecord;
use App\Models\Batch;
use App\Models\Collection;
use App\Models\InventorySummary;
use App\Models\Site;
use App\Models\SiteSaleSummary;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CollectionController extends Controller
{
    public function index(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new SiteResource($site));
            } else {
                $collections = $site->collections()->orderBy("date", "desc")->get();
                //Web Response
                return Inertia::render('Collections/Index', [
                    'site' => $site,
                    'pending' => $site->pendingCollections(),
                    "collections" => CollectionResource::collection($collections),
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

    public function show(Request $request, $code, $collection_code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            $collection = Collection::where("code", $collection_code)->first();

            if (is_object($collection)) {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(new SiteResource($site));
                } else {

                    //Web Response
                    return Inertia::render('Collections/Show', [
                        'site' => $site,
                        "collection" => new CollectionResource($collection),
                    ]);
                }
            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Collection not found"], 404);
                } else {
                    //Web Response
                    return Redirect::route('dashboard')->with('error', 'Collection not found');
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


    public function store(Request $request, $id)
    {

        $summary = SiteSaleSummary::find($id);

        //get user
        $user = (new AppController())->getAuthUser($request);


        if (is_object($summary)) {

            //Validate all the important attributes
            $request->validate([
                'quantity' => ['required'],
                'photo' => ['required'],
            ]);


            $collected_quantity = $summary->collected;
            $balance = $summary->quantity - $collected_quantity;
            if ($request->quantity == 0) {
                return Redirect::back()->with("error", "Please enter quantity collected");
            } else if ($balance < $request->quantity) {
                return Redirect::back()->with("error", "Quantity is more than what remains");
            } else {
                $balance -= $request->quantity;
                $collected_quantity += $request->quantity;
            }

            $inventorySummary = (new InventorySummaryController())->getSummary($summary->sale->site->id);

            $collection = Collection::create([
                'serial' => (new AppController())->generateUniqueCode("COLLECTION"),
                "code" => $this->getCodeNumber(),
                "client_id" => $summary->sale->client->id,
                "photo" => $request->photo,
                "collected_by" => $request->collected_by,
                "collected_by_phone_number" => $request->collected_by_phone_number,
                "inventory_id" => $summary->inventory->id,
                "inventory_summary_id" => $inventorySummary->id,
                "site_sale_summary_id" => $summary->id,
                "site_id" =>  $summary->sale->site->id,
                "quantity" => $request->quantity,
                "balance" => $balance,
                "cost" => 0,
                "user_id" => Auth::id(),
                "date"  => Carbon::now()->getTimestamp(),
            ]);

            $summary->update([
                "collected" => $collected_quantity,
            ]);

            //Update stock levels - Subtract from uncollected
            $uncollected_stock =  $collection->inventory->uncollected_stock - $collection->quantity;
            $collection->inventory->update([
                "uncollected_stock" =>   $uncollected_stock
            ]);

            //calculate cost
            $now = Carbon::now()->getTimestamp();

            $cogs_balance = $summary->inventory->cogsAccount->balance;
            $inventory_balance = $summary->inventory->inventoryAccount->balance;

            $index = 0;
            $count = 0;
            $quantity = $request->quantity;
            $total_cogs = 0;
            do {
                $batch = Batch::where("accounting_balance", ">", 0)
                    ->where("ready_date", "<=", $now)
                    ->orderBy("date", "asc")
                    ->first();

                if ($batch->accounting_balance >= $quantity) {
                    $count = $quantity;
                } else {
                    $count = $batch->accounting_balance;
                }

                $cost = $count * $batch->price;

                //record cogs
                $cogs_record = $summary->inventory->cogsAccount->records()->create([
                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    "reference" => strtoupper(""),
                    "date" => Carbon::now()->getTimestamp() + $index,
                    "name" => $summary->sale->client->name,
                    "description" => "{$summary->inventory->name} - " . $summary->inventory->formattedUnits($count),
                    "amount" => $cost,
                    "opening_balance" => $cogs_balance,
                    "closing_balance" => $cogs_balance + $cost,
                    "type" => "DEBIT", // incrementing the account balance
                    "accounting_account_id" => $summary->inventory->cogsAccount->id,
                    "summary_id" => $summary->id,
                    "collection_id" => $collection->id,
                ]);
                $cogs_balance += $cost;


                //update inventory account
                $inventory_record = $summary->inventory->inventoryAccount->records()->create([
                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    "reference" => strtoupper(""),
                    "date" => Carbon::now()->getTimestamp() + $index,
                    "name" => $summary->sale->client->name,
                    "description" => "{$summary->inventory->name} - " . $summary->inventory->formattedUnits($count),
                    "amount" => $cost,
                    "opening_balance" => $inventory_balance,
                    "closing_balance" => $inventory_balance - $cost,
                    "type" => "CREDIT", // decrementing the account balance
                    "accounting_account_id" => $summary->inventory->inventoryAccount->id,
                    "accounting_record_id" => $cogs_record->id,
                    "summary_id" => $summary->id,
                    "collection_id" => $collection->id,
                ]);
                $inventory_balance -= $cost;

                $cogs_record->update([
                    "accounting_record_id" => $inventory_record->id,
                ]);

                $batch->update([
                    "accounting_balance" => $batch->accounting_balance - $count
                ]);

                $quantity -= $count;
                $total_cogs = $cost;
                $index++;
            } while ($quantity > 0);

            $summary->inventory->cogsAccount->update([
                "balance" => $cogs_balance
            ]);

            $summary->inventory->inventoryAccount->update([
                "balance" => $inventory_balance
            ]);

            //update cost
            $collection->update([
                "cost" => $total_cogs,
            ]);

            $revenue_account = $summary->inventory->revenueAccount; // revenue account
            $revenue_account_balance =  $revenue_account->balance;
            $unearned_revenue = (new AccountingAccountController())->getAccount(2050); //unearned revenue account
            $receivables_account = (new AccountingAccountController())->getAccount(1030); //receivables account



            //update accounts
            //equivalent amount
            $amount = ($request->quantity / $summary->quantity) * $summary->amount; // calculate the amount to be moved
            $remainder = $summary->paidBalance() - $amount;

            if ($remainder >= 0) {
                //Paid enough to cover the amount delivered
                $unearned_revenue_record = AccountingRecord::create([
                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    "reference" => strtoupper(""),
                    "date" => Carbon::now()->getTimestamp(),
                    "name" => $summary->sale->client->name,
                    "description" => $summary->inventory->name . " ({$summary->formattedUnits($request->quantity)})",
                    "amount" => $amount,
                    "opening_balance" => $unearned_revenue->balance,
                    "closing_balance" => $unearned_revenue->balance - $amount,
                    "type" => "DEBIT", // decrementing the account balance
                    "accounting_account_id" => $unearned_revenue->id,
                    "summary_id" => $summary->id,
                    "collection_id" => $collection->id,
                ]);

                $unearned_revenue->update([
                    "balance" => $unearned_revenue->balance - $amount
                ]);

                $revenue_account_record = AccountingRecord::create([
                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    "reference" => strtoupper(""),
                    "date" => Carbon::now()->getTimestamp(),
                    "name" => $summary->sale->client->name,
                    "description" => $summary->inventory->name . " ({$summary->formattedUnits($request->quantity)})",
                    "amount" => $amount,
                    "opening_balance" => $revenue_account->balance,
                    "closing_balance" => $revenue_account->balance + $amount,
                    "type" => "CREDIT", // incrementing the account balance
                    "accounting_account_id" => $revenue_account->id,
                    "accounting_record_id" => $unearned_revenue_record->id,
                    "summary_id" => $summary->id,
                    "collection_id" => $collection->id,
                ]);

                $revenue_account_balance += $amount;

                $unearned_revenue_record->update([
                    "accounting_record_id" => $revenue_account_record->id,
                ]);
            } else {
                $partial_payment = $summary->paidBalance();
                $sale_balance = $amount - $partial_payment;
                if ($partial_payment > 0) {
                    //there's some amount partially paid
                    $unearned_revenue_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp(),
                        "name" => $summary->sale->client->name,
                        "description" => $summary->inventory->name . " - Partial Payment ({$summary->formattedUnits($partial_payment / ($summary->cost()))})",
                        "amount" => $partial_payment,
                        "opening_balance" => $unearned_revenue->balance,
                        "closing_balance" => $unearned_revenue->balance - $partial_payment,
                        "type" => "DEBIT", // decrementing the account balance
                        "accounting_account_id" => $unearned_revenue->id,
                        "summary_id" => $summary->id,
                        "collection_id" => $collection->id,
                    ]);

                    $unearned_revenue->update([
                        "balance" => $unearned_revenue->balance - $partial_payment
                    ]);

                    $revenue_account_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp(),
                        "name" => $summary->sale->client->name,
                        "description" => $summary->inventory->name . " - Partial Payment ({$summary->formattedUnits($partial_payment / ($summary->cost()))})",
                        "amount" => $partial_payment,
                        "opening_balance" => $revenue_account->balance,
                        "closing_balance" => $revenue_account->balance + $partial_payment,
                        "type" => "CREDIT", // incrementing the account balance
                        "accounting_account_id" => $revenue_account->id,
                        "accounting_record_id" => $unearned_revenue_record->id,
                        "summary_id" => $summary->id,
                        "collection_id" => $collection->id,
                    ]);

                    $revenue_account_balance += $partial_payment;

                    $unearned_revenue_record->update([
                        "accounting_record_id" => $revenue_account_record->id,
                    ]);
                }

                if ($sale_balance > 0) {
                    //record receivables
                    $receivables_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp(),
                        "name" => $summary->sale->client->name,
                        "description" => $summary->inventory->name . " - Partial Payment ({$summary->formattedUnits($sale_balance / ($summary->cost()))})",
                        "amount" => $sale_balance,
                        "opening_balance" => $receivables_account->balance,
                        "closing_balance" => $receivables_account->balance + $sale_balance,
                        "type" => "DEBIT", // incrementing the account balance
                        "accounting_account_id" => $receivables_account->id,
                        "summary_id" => $summary->id,
                        "collection_id" => $collection->id,
                    ]);

                    $receivables_account->update([
                        "balance" => $receivables_account->balance + $sale_balance
                    ]);

                    $revenue_account_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp(),
                        "name" => $summary->sale->client->name,
                        "description" => $summary->inventory->name . " - Partial Payment ({$summary->formattedUnits($sale_balance / ($summary->cost()))})",
                        "amount" => $sale_balance,
                        "opening_balance" => $revenue_account->balance,
                        "closing_balance" => $revenue_account->balance + $sale_balance,
                        "type" => "CREDIT", // incrementing the account balance
                        "accounting_account_id" => $revenue_account->id,
                        "accounting_record_id" => $receivables_record->id,
                        "summary_id" => $summary->id,
                        "collection_id" => $collection->id,
                    ]);

                    $revenue_account_balance += $sale_balance;

                    $receivables_record->update([
                        "accounting_record_id" => $revenue_account_record->id,
                    ]);
                }
            }

            $revenue_account->update([
                "balance" => $revenue_account_balance
            ]);





            $message = $summary->getCollectionStatus() == 2 ?
                "{$request->quantity} collected. Collection completed." :
                "{$request->quantity} collected. {$balance} is yet to be collected.";

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => $message,
                "collection_id" => $collection->id,
            ]);


            $summary->sale->update([
                "editable" => false
            ]);

            //send whatsapp notification
            (new NotificationController())->processWhatsappMessage("collection", $collection->serial, notify: $request->notify);


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new SiteSaleSummaryResource($summary), 201);
            else {
                //Web Response
                return Redirect::route('sites.summaries.show', ['code' => $summary->sale->site->code, 'id' => $inventorySummary->id])->with('success', 'Collection recorded!');
            }
        } else {
            return Redirect::back()->with('error', 'Resource not found');
        }
    }

    public function trash(Request $request, $code)
    {
        $collection = Collection::where("code", $code)->first();

        if (is_object($collection)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                //                return response()->json(new SaleResource($summary));
            } else {

                $quantity = $collection->quantity;

                //undo batch update
                Batch::create([
                    "date" => $collection->date,
                    "ready_date" => $collection->date,
                    "price" =>  $collection->cost / $quantity,
                    "quantity" => $quantity,
                    "accounting_balance" => $quantity,
                    "balance" =>  0,
                    "photo" => $collection->photo,
                    "comments" => $collection->comments,
                    "inventory_id" => $collection->inventory->id,
                    "user_id" => Auth::id(),
                ]);

                //summary undo
                $collected =  $collection->siteSaleSummary->collected - $quantity;
                $collection->siteSaleSummary->update([
                    "collected" =>   $collected
                ]);

                //inventory undo
                $collection->inventory->update([
                    'uncollected_stock' => $collection->inventory->uncollected_stock + $quantity,
                ]);

                //reverse transactions
                (new AccountingRecordController())->reverseTransactions($collection->records, null, $collection->id);

                //send whatsapp notification
                $balance =  $collection->siteSaleSummary->quantity - $collected;
                (new NotificationController())->processWhatsappMessage("collection_reversal", $collection->serial, balance: $balance);

                  $sale_id = $collection->siteSaleSummary->sale->id;
                  $site_code = $collection->site->code;
                $collection->delete();

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Collection has been deleted",
                    "site_sale_id" => $sale_id,
                ]);

                return Redirect::route('sites.collections',["code"=>$site_code])->with('success', 'Delivery deleted successfully');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Collection not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Collection not found');
            }
        }
    }
    public function cancel(Request $request, $id)
    {
        $summary = SiteSaleSummary::find($id);

        if (is_object($summary)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                //                return response()->json(new SaleResource($summary));
            } else {

                $balance = $summary->quantity - $summary->collected;
                $inventory_available_stock = $summary->inventory->available_stock;
                $inventory_uncollected_stock = $summary->inventory->uncollected_stock;

                $summary->inventory->update([
                    'available_stock' => $inventory_available_stock + $balance,
                    'uncollected_stock' => $inventory_uncollected_stock - $balance,

                ]);

                $summary->delete();

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Collection has been cancelled",
                    "site_sale_id" => $summary->sale->id,
                ]);

                return Redirect::back()->with('success', 'Delivery cancelled successfully');
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

    private function getCodeNumber()
    {
        $last = Collection::orderBy("code", "desc")->withTrashed()->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }
}
