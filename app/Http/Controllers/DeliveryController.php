<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryResource;
use App\Http\Resources\RequestFormResource;
use App\Http\Resources\SaleResource;
use App\Models\AccountingRecord;
use App\Models\Delivery;
use App\Models\Expense;
use App\Models\Payable;
use App\Models\Summary;
use App\Models\Supplier;
use App\Models\SystemLog;
use App\Models\Transporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DeliveryController extends Controller
{

    public function index(Request $request)
    {
        $filter = strtolower($request->query("filter"));
        if ($filter == "all") {
            $deliveries = Delivery::where("status", ">", 0)->orderBy("due_date", "desc")->paginate(100);
            $headline = "all";
        } else if ($filter == "completed") {
            $deliveries = Delivery::where("status", 4)->orderBy("due_date", "desc")->paginate(100);
            $headline = "completed";
        } else if ($filter == "cancelled") {
            $deliveries = Delivery::where("status", 3)->orderBy("due_date", "desc")->paginate(100);
            $headline = "cancelled";
        } else if ($filter == "delivered") {
            $deliveries = Delivery::where("status", 2)->orderBy("due_date", "desc")->paginate(100);
            $headline = "delivered";
        } else {
            $deliveries = Delivery::where("status", 1)->orderBy("due_date", "desc")->paginate(100);
            $headline = "processing";
        }

        //        dd((new AppController())->generateCompound(DeliveryResource::collection($deliveries)));

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(DeliveryResource::collection($deliveries));
        else {
            //Web Response
            return Inertia::render('Deliveries/Index', [
                //                'deliveries' => (new AppController())->generateCompound(DeliveryResource::collection($deliveries)),
                'deliveries' => DeliveryResource::collection($deliveries),
                'headline' => $headline
            ]);
        }
    }


    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $delivery = Delivery::find($id);
        $transporters = Transporter::orderBy("name", "asc")->get();
        $suppliers = Supplier::orderBy("name", "asc")->get();

        if (is_object($delivery)) {

            if ($delivery->status == 0) {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Delivery not initiated"], 404);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Delivery not initiated');
                }
            } else if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new DeliveryResource($delivery));
            } else {
                //Web Response
                return Inertia::render('Deliveries/Show', [
                    'delivery' => new DeliveryResource($delivery),
                    'requestForms' => RequestFormResource::collection($delivery->requestForms),
                    'transporters' => $transporters,
                    'suppliers' => $suppliers,
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Delivery not found"], 404);
            } else {
                //Web Response
                return Redirect::route('deliveries.index')->with('error', 'Delivery not found');
            }
        }
    }

    public function update(Request $request, $id)
    {

        $summary = Summary::find($id);

        //get user
        $user = (new AppController())->getAuthUser($request);


        if (is_object($summary)) {

            if ($summary->delivery == null) {
                //Validate all the important attributes
                $request->validate([
                    'delivery_date' => ['required'],
                    'waiver' => ['required'],
                ]);

                if ($this->getPaymentStatus($summary->amount, $summary->balance) == 0 && !$request->waiver) {
                    return Redirect::back()->with("error", "Product has not been paid for. Please update payment status first.");
                }

                if ($summary->product->id != (new AppController())->SERVICES_PRODUCT_ID) {
                    $delivery = Delivery::create([
                        "code" => $this->getCodeNumber(),
                        'serial' => (new AppController())->generateUniqueCode("DELIVERY"),
                        "status" => 1,
                        "quantity_delivered" => 0,
                        "summary_id" => $summary->id,
                        "tracking_number" => uniqid(),
                        "due_date" => $request->delivery_date,
                    ]);

                    $summary->update([
                        "status" => 1 //delivery for sale has been initiated
                    ]);

                    //Logging
                    SystemLog::create([
                        "user_id" => Auth::id(),
                        "message" => "Delivery for {$summary->fullName()} has been initiated. Quantity to be delivered is {$summary->quantity}",
                        "delivery_id" => $delivery->id,
                    ]);
                }
            } else if ($summary->delivery->status == 0) {
                //Validate all the important attributes
                $request->validate([
                    'delivery_date' => ['required'],
                    'waiver' => ['required'],
                ]);

                if ($this->getPaymentStatus($summary->amount, $summary->balance) == 0 && !$request->waiver) {
                    return Redirect::back()->with("error", "Product has not been paid for. Please update payment status first.");
                }

                $summary->delivery->update([
                    "code" => $this->getCodeNumber(),
                    "status" => 1,
                    "due_date" => $request->delivery_date,
                    //                    "initiated_by" => isset($request->user_id) ? $request->user_id : $user->id
                ]);

                $summary->update([
                    "status" => 1 //delivery for sale has been initiated
                ]);

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Delivery for {$summary->fullName()} has been initiated. Quantity to be delivered is {$summary->quantity}",
                    "delivery_id" => $summary->delivery->id,
                ]);
            } else {

                //Validate all the important attributes
                $request->validate([
                    'quantity' => ['required'],
                    'photo' => ['required'],
                    'recipient_name' => ['required'],
                    'recipient_phone_number' => ['required'],
                ]);


                $delivered_quantity = $summary->delivery->quantity_delivered;
                $balance = $summary->quantity - $delivered_quantity;
                if ($request->quantity == 0) {
                    return Redirect::back()->with("error", "Please enter quantity delivered");
                } else if ($balance < $request->quantity) {
                    return Redirect::back()->with("error", "Quantity is more than what is required");
                } else {
                    $balance -= $request->quantity;
                    $delivered_quantity += $request->quantity;
                }


                $notes = [];
                if ($summary->delivery->notes != null) {
                    $notes = json_decode($summary->delivery->notes);
                }

                $notes[] = [
                    "quantity" => floatval($request->quantity),
                    "cost" => $request->cost,
                    "total" => ($request->quantity / $summary->quantity) * $summary->amount,
                    "balance" => $balance,
                    "photo" => $request->photo,
                    "recipientName" => $request->recipient_name,
                    "recipientPhoneNumber" => $request->recipient_phone_number,
                    "date"  => Carbon::now()->getTimestamp(),
                ];


                //record cogs
                $cogs_record = $summary->product->cogsAccount->records()->create([
                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    "reference" => strtoupper(""),
                    "date" => Carbon::now()->getTimestamp(),
                    "name" => $summary->sale->client->name,
                    "description" => $summary->description,
                    "amount" => $request->cost,
                    "opening_balance" => $summary->product->cogsAccount->balance,
                    "closing_balance" => $summary->product->cogsAccount->balance + $request->cost,
                    "type" => "DEBIT", // incrementing the account balance
                    "accounting_account_id" => $summary->product->cogsAccount->id,
                    "summary_id" => $summary->id
                ]);
                $summary->product->cogsAccount->update([
                    "balance" => $summary->product->cogsAccount->balance + $request->cost
                ]);

                //update inventory account
                $inventory_record = $summary->product->inventoryAccount->records()->create([
                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                    "reference" => strtoupper(""),
                    "date" => Carbon::now()->getTimestamp(),
                    "name" => $summary->sale->client->name,
                    "description" => $summary->description,
                    "amount" => $request->cost,
                    "opening_balance" => $summary->product->inventoryAccount->balance,
                    "closing_balance" => $summary->product->inventoryAccount->balance - $request->cost,
                    "type" => "CREDIT", // decrementing the account balance
                    "accounting_account_id" => $summary->product->inventoryAccount->id,
                    "accounting_record_id" => $cogs_record->id,
                    "summary_id" => $summary->id
                ]);
                $summary->product->inventoryAccount->update([
                    "balance" => $summary->product->inventoryAccount->balance - $request->cost
                ]);

                $cogs_record->update([
                    "accounting_record_id" => $inventory_record->id,
                ]);


                $revenue_account = $summary->product->revenueAccount; // revenue account
                $revenue_account_balance =  $revenue_account->balance;
                $unearned_revenue = (new AccountingAccountController())->getAccount(2050); //unearned revenue account
                $receivables_account = (new AccountingAccountController())->getAccount(1030); //receivables account

                //update accounts
                $amount = ($request->quantity / $summary->quantity) * $summary->amount; // calculate the amount to be moved
                $remainder = $summary->paidBalance() - $amount;


                if ($remainder >= 0) {
                    //Paid enough to cover the amount delivered
                    $unearned_revenue_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp(),
                        "name" => $summary->sale->client->name,
                        "description" => $summary->description . "({$summary->formattedUnits($request->quantity)})",
                        "amount" => $amount,
                        "opening_balance" => $unearned_revenue->balance,
                        "closing_balance" => $unearned_revenue->balance - $amount,
                        "type" => "DEBIT", // decrementing the account balance
                        "accounting_account_id" => $unearned_revenue->id,
                        "summary_id" => $summary->id
                    ]);

                    $unearned_revenue->update([
                        "balance" => $unearned_revenue->balance - $amount
                    ]);

                    $revenue_account_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp(),
                        "name" => $summary->sale->client->name,
                        "description" => $summary->description . "({$summary->formattedUnits($request->quantity)})",
                        "amount" => $amount,
                        "opening_balance" => $revenue_account->balance,
                        "closing_balance" => $revenue_account->balance + $amount,
                        "type" => "CREDIT", // incrementing the account balance
                        "accounting_account_id" => $revenue_account->id,
                        "accounting_record_id" => $unearned_revenue_record->id,
                        "summary_id" => $summary->id
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
                            "description" => $summary->description . " - Partial Payment ({$summary->formattedUnits($partial_payment / ($summary->amount /$summary->quantity))})",
                            "amount" => $partial_payment,
                            "opening_balance" => $unearned_revenue->balance,
                            "closing_balance" => $unearned_revenue->balance - $partial_payment,
                            "type" => "DEBIT", // decrementing the account balance
                            "accounting_account_id" => $unearned_revenue->id,
                            "summary_id" => $summary->id
                        ]);

                        $unearned_revenue->update([
                            "balance" => $unearned_revenue->balance - $partial_payment
                        ]);

                        $revenue_account_record = AccountingRecord::create([
                            "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                            "reference" => strtoupper(""),
                            "date" => Carbon::now()->getTimestamp(),
                            "name" => $summary->sale->client->name,
                            "description" => $summary->description . " - Partial Payment ({$summary->formattedUnits($partial_payment / ($summary->amount /$summary->quantity))})",
                            "amount" => $partial_payment,
                            "opening_balance" => $revenue_account->balance,
                            "closing_balance" => $revenue_account->balance + $partial_payment,
                            "type" => "CREDIT", // incrementing the account balance
                            "accounting_account_id" => $revenue_account->id,
                            "accounting_record_id" => $unearned_revenue_record->id,
                            "summary_id" => $summary->id
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
                            "description" => $summary->description . " - Partial Payment ({$summary->formattedUnits($sale_balance / ($summary->amount /$summary->quantity))})",
                            "amount" => $sale_balance,
                            "opening_balance" => $receivables_account->balance,
                            "closing_balance" => $receivables_account->balance + $sale_balance,
                            "type" => "DEBIT", // incrementing the account balance
                            "accounting_account_id" => $receivables_account->id,
                            "summary_id" => $summary->id
                        ]);

                        $receivables_account->update([
                            "balance" => $receivables_account->balance + $sale_balance
                        ]);

                        $revenue_account_record = AccountingRecord::create([
                            "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                            "reference" => strtoupper(""),
                            "date" => Carbon::now()->getTimestamp(),
                            "name" => $summary->sale->client->name,
                            "description" => $summary->description . " - Partial Payment ({$summary->formattedUnits($sale_balance / ($summary->amount /$summary->quantity))})",
                            "amount" => $sale_balance,
                            "opening_balance" => $revenue_account->balance,
                            "closing_balance" => $revenue_account->balance + $sale_balance,
                            "type" => "CREDIT", // incrementing the account balance
                            "accounting_account_id" => $revenue_account->id,
                            "accounting_record_id" => $receivables_record->id,
                            "summary_id" => $summary->id
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

                $summary->delivery->update([
                    "status" => $balance == 0 ? 2 : 1,
                    "quantity_delivered" => $delivered_quantity,
                    "notes" => json_encode($notes)
                ]);

                $message = $summary->delivery->status == 2 ?
                    "{$request->quantity} delivered. Delivery Completed." :
                    "{$request->quantity} delivered. {$balance} is yet to be delivered.";

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => $message,
                    "delivery_id" => $summary->delivery->id,
                ]);
            }



            $summary->sale->update([
                "editable" => false
            ]);

            // $filter = $summary->delivery->status == 1 ? "processing" : "completed";

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new SaleResource($summary), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', 'Delivery initiated!');
            }
        } else {
            return Redirect::back()->with('error', 'Delivery not found');
        }
    }

    public function cancel(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (is_object($delivery)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                //                return response()->json(new SaleResource($delivery));
            } else {

                $delivery->update([
                    "status" => 3
                ]);

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Delivery has been cancelled",
                    "delivery_id" => $delivery->id,
                ]);

                return Redirect::back()->with('success', 'Delivery cancelled successfully');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Delivery not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Delivery not found');
            }
        }
    }

    public function complete(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (is_object($delivery)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                //                return response()->json(new SaleResource($delivery));
            } else {

                $request->validate([
                    //                    'product' => ['required'],
                    //                    'transportation' => ['required'],
                ]);

                foreach ($request->payables as $payable) {
                    Payable::create([
                        "code" => (new PayableController())->getCodeNumber(),
                        "description" => $payable["name"],
                        "total" => $payable["amount"],
                        "date" => $payable["date"],
                        "contents" => json_encode([]),
                        "expense_type_id" => env('OPERATIONS_EXPENSE_TYPE_ID'),
                        "transporter_id" => $payable["transporterId"] ?? null,
                        "supplier_id" => $payable["supplierId"] ?? null,
                        "delivery_id" => $delivery->id,
                        "sale_id" => $delivery->summary->sale->id,
                        "paid" => false,
                    ]);
                }

                $delivery->update([
                    "status" => 4
                ]);

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Delivery has been completed",
                    "delivery_id" => $delivery->id,
                ]);

                return Redirect::back()->with('success', 'Delivery completed successfully');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Delivery not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Delivery not found');
            }
        }
    }

    public function print(Request $request, $id)
    {
        //find out if the request is valid
        $delivery = Delivery::find($id);

        if (is_object($delivery)) {

            $filename = "DELIVERY-NOTE#" . (new AppController())->getZeroedNumber($delivery->code) . " - " . $delivery->summary->sale->client->name . "-" . date('Ymd', $delivery->summary->sale->date);

            $now_d = \Illuminate\Support\Carbon::createFromTimestamp($delivery->summary->sale->date, 'Africa/Lusaka')->format('F j, Y');
            $now_t = Carbon::createFromTimestamp($delivery->summary->sale->date, 'Africa/Lusaka')->format('H:i');

            $pdf = PDF::loadView('delivery', [
                'date'              => $now_d,
                'time'              => $now_t,
                'delivery'           => $delivery,
            ]);
            return $pdf->download("$filename.pdf");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Delivery not found"], 404);
            } else {


                //Web Response
                return Redirect::route('dashboard')->with('error', 'Delivery not found');
            }
        }
    }

    public function getCodeNumber()
    {
        $last = Delivery::orderBy("code", "desc")->first();
        if (is_object($last)) {

            return $last->code != null ? $last->code + 1 : 1;
        } else {
            return 1;
        }
    }

    public function getPaymentStatus($amount, $balance): int
    {
        //        dump($balance);
        if (isset($balance)) {
            if ($balance == $amount) {
                return 0;
            } elseif ($balance > 0 && $balance < $amount) {
                return 1;
            } elseif ($balance == 0) {
                return 2;
            }
        }
        return 3;
    }
}
