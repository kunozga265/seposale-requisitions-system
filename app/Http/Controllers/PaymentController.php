<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleResource;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentReceipt;
use App\Models\Sale;
use App\Models\AccountingAccount;
use App\Models\AccountingRecord;
use App\Models\Receipt;
use App\Models\ReceiptSummary;
use App\Models\Summary;
use App\Models\SystemLog;
use Carbon\Carbon;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PaymentController extends Controller
{


    public function runCallback($payment, $sale)
    {
        $access_token = $this->getAccessToken();

        Log::info("Access Token:  $access_token");

        if ($access_token === null) {
            return Redirect::route('home')->with("error", "Failed to process payment. Please try again later.");
        }

        $client = new Client();

        $url = config('app.standard_bank.gateway_url') .  "transactions/outlets/" . config('app.standard_bank.outlet_id') . "/orders/" . $payment->reference;
        // Log::info($url);
        // Log::info($body);
        $response = $client->request('GET', $url, [
            'headers' => [
                'accept' => 'application/vnd.ni-payment.v2+json',
                'content-type' => 'application/vnd.ni-payment.v2+json',
                'authorization' => 'Bearer ' . $access_token,
            ],
        ]);

        $res_body = json_decode($response->getBody());

        $links = $res_body->_links;

        Log::info(json_encode($res_body));

        // dd($res_body);

        $content = json_decode($payment->content, true);
        $items = $content['items'];
        $content['statuses'][] = json_encode($res_body);

        $state = $res_body->_embedded->payment[0]->state;
        $status = $res_body->_embedded->payment[0]->_embedded->{'cnp:capture'}[0]->state;

        $payment->update([
            'state' => $state,
            'status' => $status,
            'content' => json_encode($content),
        ]);

        if ($status == 'SUCCESS') {

            //generate receipt
            $this->generateReceipt($sale, $payment, $items);

            $amount = $res_body->amount->value;
            $amount_formatted = $res_body->formattedAmount;
            (new NotificationController())->notifySales($sale, "payment-success", extra: $amount_formatted);

            // $payment_method = PaymentMethod::where('name', 'Standard Bank')->first();

            // PaymentReceipt::create([
            //     'date' => Carbon::now()->getTimestamp(),
            //     'amount' => $amount,
            //     'file' => null,
            //     'description' => "Paid through Standard Bank API. \nReference: $reference \nAmount: $amount_formatted",
            //     'payment_method_id' => $payment_method->id,
            //     'sale_id' => $sale->id,
            //     'site_sale_id' => null,
            //     'user_id' => 0,
            // ]);

            // (new NotificationController())->notifyAccounts(
            //     $sale,
            //     "proof_of_payment",
            //     amount: $amount,
            // );
        }

        return $status;
    }


    private function getAccessToken()
    {
        try {
            $client = new Client();
            $url = config('app.standard_bank.gateway_url') . "identity/auth/access-token";
            $response = $client->request('POST', $url, [
                'headers' => [
                    'accept' => 'application/vnd.ni-identity.v1+json',
                    'content-type' => 'application/vnd.ni-identity.v1+json',
                    'authorization' => 'Basic ' . config('app.standard_bank.api_key'),
                ],
            ]);

            $res_body = json_decode($response->getBody());
            return $res_body?->access_token;
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
        }

        return null;
    }

    public function generateReceipt($sale, $payment, $items)
    {
        $type = "ORDINARY";

        $receipt = Cache::lock($sale->serial . ':receipt:store', 10)->get(function () use ($sale, $payment, $items, $type) {

            //Checking
            $total = 0;
            $filteredProducts = [];

            foreach ($items as $item) {
                $amount = $item["amount"];
                $total += $amount;

                $summary = Summary::findOrFail($item["id"]);
                if (isset($summary->balance)) {
                    $balance = $summary->balance - $amount;
                    if ($balance < 0) {
                        return Redirect::route('sales.show', ['serial' => $sale->serial])->with("error", "Payment is more than what is required. Please contact sales team.");
                    }
                }
                if ($amount > 0) {
                    $filteredProducts[] = $item;
                }
            }

            $new_balance = $sale->balance - $total;
            if ($new_balance < 0) {
                return Redirect::route('sales.show', ['serial' => $sale->serial])->with("error", "Payment is more than what is required");
            } else if ($total <= 0) {
                return Redirect::route('sales.show', ['serial' => $sale->serial])->with("error", "Receipt amount is zero");
            }



            $wallet_account = (new AccountingAccountController())->getAccount(1021);
            $payment_method = PaymentMethod::where('name', 'Standard Bank Online')->first();


            // $receipt = Receipt::find(1);
            $receipt = Receipt::create([
                'code' => $this->getCodeReceiptNumber(),
                'serial' => (new AppController())->generateUniqueCode("RECEIPT"),
                'client_id' => $sale->client->id,
                // "sale_id" =>  $type == "ORDINARY" ? $sale->id : null,
                // "site_sale_id" =>  $type == "SITE" ? $sale->id : null,
                'account_id' => $wallet_account->id,
                'payment_method_id' => $payment_method->id,
                'amount' => $total,
                'reference' => strtoupper($payment->reference),
                // 'information' => json_encode($filteredProducts),
                'user_id' => 0,
                'date' => \Carbon\Carbon::now()->getTimestamp(),
            ]);


            $unearned_revenue_account = (new AccountingAccountController())->getAccount(2050); //unearned revenue account
            $receivables_account = (new AccountingAccountController())->getAccount(1030); //receivables account

            $wallet_account_balance = $wallet_account->balance;
            $unearned_revenue_balance = $unearned_revenue_account->balance;
            $receivables_balance = $receivables_account->balance;

            $index = 0;
            //Updating data
            foreach ($filteredProducts as $item) {
                $amount = $item["amount"];
                $summary =  Summary::findOrFail($item["id"]);
                $paid_balance = $summary->paidBalance();

                //   if ($paid_balance < 0) {
                //     $remainder = $amount - abs($paid_balance);
                //     $partial_payment = abs($paid_balance);
                //   }

                //  dump("paid_balance: $paid_balance");
                //  dump("partial_payment: $partial_payment");
                //  dd("remainder: $remainder");

                if (isset($summary->balance)) {
                    $balance = $summary->balance - $amount;

                    //create receipt transaction
                    $receiptSummary = ReceiptSummary::create([
                        "balance" => $balance,
                        "amount" => $amount,
                        "cost" => $summary->cost(),
                        "units" => $summary->units,
                        "receipt_id" => $receipt->id,
                    ]);

                    $sale->update([
                        "balance" => $new_balance,
                        "editable" => false,
                        "status" => $new_balance == 0 ? 2 : 1
                    ]);


                    $summary->update([
                        "balance" => $balance
                    ]);

                    switch ($type) {
                        case "ORDINARY":
                            $summary_id = $summary->id;
                            $sale_id = $summary->sale->id;

                            $site_sale_id = $summary->siteSaleSummary?->sale->id;
                            $site_sale_summary_id = $summary->siteSaleSummary?->id;

                            $summary->siteSaleSummary?->update([
                                "balance" => $balance
                            ]);

                            $summary->siteSaleSummary?->sale->update([
                                "balance" => $new_balance,
                                "editable" => false,
                                "status" => $new_balance == 0 ? 2 : 1
                            ]);


                            break;
                        default:
                            $summary_id = $summary->summary?->id;
                            $sale_id = $summary->summary?->sale->id;

                            $site_sale_id = $summary->sale->id;
                            $site_sale_summary_id = $summary->id;

                            $summary->summary?->update([
                                "balance" => $balance
                            ]);

                            $summary->summary?->sale->update([
                                "balance" => $new_balance,
                                "editable" => false,
                                "status" => $new_balance == 0 ? 2 : 1
                            ]);
                    }



                    $receipt->update([
                        "sale_id" =>  $sale_id,
                        "site_sale_id" =>  $site_sale_id,
                    ]);

                    $receiptSummary->update([
                        "name" => $summary->description(),
                        "summary_id" => $summary_id,
                        "site_sale_summary_id" => $site_sale_summary_id,
                    ]);



                    //check if there is a delivery on delivery or collection end proportionate the amount that neeeds to go to receivables
                    if ($summary->deliveryExists() || $summary->getCollectionStatus() > 0) {

                        //client owes us
                        if ($paid_balance < 0) {
                            $remainder = $amount - abs($paid_balance);
                            $partial_payment = abs($paid_balance);

                            if ($remainder <= 0) {
                                //debit cash account
                                $wallet_record = AccountingRecord::create([
                                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                    "reference" => strtoupper($receipt->reference),
                                    "date" => $receipt->date + $index,
                                    "name" => $receipt->client->name,
                                    "description" => $receiptSummary->name . "({$summary->formattedUnits($amount / ($summary->amount /$summary->quantity))})",
                                    "amount" => $amount,
                                    "opening_balance" => $wallet_account_balance,
                                    "closing_balance" => $wallet_account_balance + $amount,
                                    "type" => "DEBIT", // incrementing the account balance
                                    "accounting_account_id" => $wallet_account->id,
                                    "receipt_id" => $receipt->id,
                                    "receipt_summary_id" => $receiptSummary->id,
                                ]);
                                $wallet_account_balance += $amount;

                                //credit receivables
                                $receivables_record = AccountingRecord::create([
                                    "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                    "reference" => strtoupper($receipt->reference),
                                    "date" => $receipt->date + $index,
                                    "name" => $receipt->client->name,
                                    "description" => $receiptSummary->name . "({$summary->formattedUnits($amount / ($summary->amount /$summary->quantity))})",
                                    "amount" => $amount,
                                    "opening_balance" => $receivables_balance,
                                    "closing_balance" => $receivables_balance - $amount,
                                    "type" => "CREDIT", // incrementing the account balance
                                    "accounting_account_id" => $receivables_account->id,
                                    "accounting_record_id" => $wallet_record->id,
                                    "receipt_id" => $receipt->id,
                                    "receipt_summary_id" => $receiptSummary->id,
                                ]);
                                $receivables_balance -= $amount;

                                $wallet_record->update([
                                    "accounting_record_id" => $receivables_record->id
                                ]);

                                switch ($type) {
                                    case "ORDINARY":
                                        $wallet_record->update([
                                            "summary_id" => $summary->id,
                                        ]);
                                        $receivables_record->update([
                                            "summary_id" => $summary->id,
                                        ]);
                                        break;
                                    default:
                                        $wallet_record->update([
                                            "site_sale_summary_id" => $summary->id,
                                        ]);
                                        $receivables_record->update([
                                            "site_sale_summary_id" => $summary->id,
                                        ]);
                                }
                            } else {
                                // partly debit cash and credit receivables
                                //debit cash account
                                if ($partial_payment > 0) {
                                    $wallet_record = AccountingRecord::create([
                                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                        "reference" => strtoupper($receipt->reference),
                                        "date" => $receipt->date + $index,
                                        "name" => $receipt->client->name,
                                        // "description" => $receiptSummary->name,
                                        "description" => $receiptSummary->name . "({$summary->formattedUnits($partial_payment / ($summary->amount /$summary->quantity))})",
                                        "amount" => $partial_payment,
                                        "opening_balance" => $wallet_account_balance,
                                        "closing_balance" => $wallet_account_balance + $partial_payment,
                                        "type" => "DEBIT", // incrementing the account balance
                                        "accounting_account_id" => $wallet_account->id,
                                        "receipt_id" => $receipt->id,
                                        "receipt_summary_id" => $receiptSummary->id,
                                    ]);
                                    $wallet_account_balance += $partial_payment;

                                    //credit receivables
                                    $receivables_record = AccountingRecord::create([
                                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                        "reference" => strtoupper($receipt->reference),
                                        "date" => $receipt->date + $index,
                                        "name" => $receipt->client->name,
                                        "description" => $receiptSummary->name . "({$summary->formattedUnits($partial_payment / ($summary->amount /$summary->quantity))})",
                                        "amount" => $partial_payment,
                                        "opening_balance" => $receivables_balance,
                                        "closing_balance" => $receivables_balance - $partial_payment,
                                        "type" => "CREDIT", // incrementing the account balance
                                        "accounting_account_id" => $receivables_account->id,
                                        "accounting_record_id" => $wallet_record->id,
                                        "receipt_id" => $receipt->id,
                                        "receipt_summary_id" => $receiptSummary->id,
                                    ]);
                                    $receivables_balance -= $partial_payment;

                                    $wallet_record->update([
                                        "accounting_record_id" => $receivables_record->id
                                    ]);

                                    switch ($type) {
                                        case "ORDINARY":
                                            $wallet_record->update([
                                                "summary_id" => $summary->id,
                                            ]);
                                            $receivables_record->update([
                                                "summary_id" => $summary->id,
                                            ]);
                                            break;
                                        default:
                                            $wallet_record->update([
                                                "site_sale_summary_id" => $summary->id,
                                            ]);
                                            $receivables_record->update([
                                                "site_sale_summary_id" => $summary->id,
                                            ]);
                                    }

                                    $index++;
                                }

                                //Handle balance
                                if ($remainder > 0) {
                                    $wallet_record = AccountingRecord::create([
                                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                        "reference" => strtoupper($receipt->reference),
                                        "date" => $receipt->date + $index,
                                        "name" => $receipt->client->name,
                                        "description" => $receiptSummary->name . "({$summary->formattedUnits($remainder / ($summary->amount /$summary->quantity))})",
                                        "amount" => $remainder,
                                        "opening_balance" => $wallet_account_balance,
                                        "closing_balance" => $wallet_account_balance + $remainder,
                                        "type" => "DEBIT", // incrementing the account balance
                                        "accounting_account_id" => $wallet_account->id,
                                        "receipt_id" => $receipt->id,
                                        "receipt_summary_id" => $receiptSummary->id,
                                    ]);
                                    $wallet_account_balance += $remainder;

                                    $unearned_revenue_record = AccountingRecord::create([
                                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                        "reference" => strtoupper($receipt->reference),
                                        "date" => $receipt->date + $index,
                                        "name" => $receipt->client->name,
                                        "description" => $receiptSummary->name . "({$summary->formattedUnits($remainder / ($summary->amount /$summary->quantity))})",
                                        "amount" => $remainder,
                                        "opening_balance" => $unearned_revenue_balance,
                                        "closing_balance" => $unearned_revenue_balance + $remainder,
                                        "type" => "CREDIT", // incrementing the account balance
                                        "accounting_account_id" => $unearned_revenue_account->id,
                                        "accounting_record_id" => $wallet_record->id,
                                        "receipt_id" => $receipt->id,
                                        "receipt_summary_id" => $receiptSummary->id,
                                    ]);
                                    $unearned_revenue_balance += $remainder;

                                    $wallet_record->update([
                                        "accounting_record_id" => $unearned_revenue_record->id
                                    ]);

                                    switch ($type) {
                                        case "ORDINARY":
                                            $wallet_record->update([
                                                "summary_id" => $summary->id,
                                            ]);
                                            $unearned_revenue_record->update([
                                                "summary_id" => $summary->id,
                                            ]);
                                            break;
                                        default:
                                            $wallet_record->update([
                                                "site_sale_summary_id" => $summary->id,
                                            ]);
                                            $unearned_revenue_record->update([
                                                "site_sale_summary_id" => $summary->id,
                                            ]);
                                    }
                                }
                            }
                        } else {
                            $wallet_record = AccountingRecord::create([
                                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                "reference" => strtoupper($receipt->reference),
                                "date" => $receipt->date + $index,
                                "name" => $receipt->client->name,
                                "description" => $receiptSummary->name . "({$summary->formattedUnits($amount / ($summary->amount /$summary->quantity))})",
                                "amount" => $amount,
                                "opening_balance" => $wallet_account_balance,
                                "closing_balance" => $wallet_account_balance + $amount,
                                "type" => "DEBIT", // incrementing the account balance
                                "accounting_account_id" => $wallet_account->id,
                                "receipt_id" => $receipt->id,
                                "receipt_summary_id" => $receiptSummary->id,
                            ]);
                            $wallet_account_balance += $amount;

                            $unearned_revenue_record = AccountingRecord::create([
                                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                                "reference" => strtoupper($receipt->reference),
                                "date" => $receipt->date + $index,
                                "name" => $receipt->client->name,
                                "description" => $receiptSummary->name . "({$summary->formattedUnits($amount / ($summary->amount /$summary->quantity))})",
                                "amount" => $amount,
                                "opening_balance" => $unearned_revenue_balance,
                                "closing_balance" => $unearned_revenue_balance + $amount,
                                "type" => "CREDIT", // incrementing the account balance
                                "accounting_account_id" => $unearned_revenue_account->id,
                                "accounting_record_id" => $wallet_record->id,
                                "receipt_id" => $receipt->id,
                                "receipt_summary_id" => $receiptSummary->id,
                            ]);
                            $unearned_revenue_balance += $amount;

                            $wallet_record->update([
                                "accounting_record_id" => $unearned_revenue_record->id
                            ]);

                            switch ($type) {
                                case "ORDINARY":
                                    $wallet_record->update([
                                        "summary_id" => $summary->id,
                                    ]);
                                    $unearned_revenue_record->update([
                                        "summary_id" => $summary->id,
                                    ]);
                                    break;
                                default:
                                    $wallet_record->update([
                                        "site_sale_summary_id" => $summary->id,
                                    ]);
                                    $unearned_revenue_record->update([
                                        "site_sale_summary_id" => $summary->id,
                                    ]);
                            }
                        }
                    } else {
                        //there is no delivery or collection, credit unearned revenue
                        $wallet_record = AccountingRecord::create([
                            "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                            "reference" => strtoupper($receipt->reference),
                            "date" => $receipt->date + $index,
                            "name" => $receipt->client->name,
                            "description" => $receiptSummary->name . "({$summary->formattedUnits($amount / ($summary->amount /$summary->quantity))})",
                            "amount" => $amount,
                            "opening_balance" => $wallet_account_balance,
                            "closing_balance" => $wallet_account_balance + $amount,
                            "type" => "DEBIT", // incrementing the account balance
                            "accounting_account_id" => $wallet_account->id,
                            "receipt_id" => $receipt->id,
                            "receipt_summary_id" => $receiptSummary->id,
                        ]);
                        $wallet_account_balance += $amount;

                        $unearned_revenue_record = AccountingRecord::create([
                            "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                            "reference" => strtoupper($receipt->reference),
                            "date" => $receipt->date + $index,
                            "name" => $receipt->client->name,
                            "description" => $receiptSummary->name . "({$summary->formattedUnits($amount / ($summary->amount /$summary->quantity))})",
                            "amount" => $amount,
                            "opening_balance" => $unearned_revenue_balance,
                            "closing_balance" => $unearned_revenue_balance + $amount,
                            "type" => "CREDIT", // incrementing the account balance
                            "accounting_account_id" => $unearned_revenue_account->id,
                            "accounting_record_id" => $wallet_record->id,
                            "receipt_id" => $receipt->id,
                            "receipt_summary_id" => $receiptSummary->id,
                        ]);
                        $unearned_revenue_balance += $amount;

                        $wallet_record->update([
                            "accounting_record_id" => $unearned_revenue_record->id
                        ]);

                        $wallet_record->update([
                            "summary_id" => $summary->id,
                        ]);
                        $unearned_revenue_record->update([
                            "summary_id" => $summary->id,
                        ]);
                    }

                    $index++;
                }
            }

            //Update the account balance
            $wallet_account->update([
                "balance" => $wallet_account_balance
            ]);

            //Update the account balance
            $unearned_revenue_account->update([
                "balance" => $unearned_revenue_balance
            ]);

            //Update the account balance
            $receivables_account->update([
                "balance" => $receivables_balance
            ]);

            //


            // Transaction::create([
            //     "date" => $receipt->date,
            //     "reference" => strtoupper($receipt->reference),
            //     "description" => $receipt->listOfProducts(),
            //     "from_to" => $receipt->client->name,
            //     "expense_id" => null,
            //     "receipt_id" => $receipt->id,
            //     "account_id" => $receipt->account->id,
            //     "total" => $receipt->amount,
            //     "balance" => $balance,
            //     "type" => "CREDIT",
            // ]);

            //Logging
            SystemLog::create([
                "user_id" => 0,
                "message" => "Receipt #{$receipt->code} created for {$sale->client->name} under Sale #{$sale->code_alt}. Total amount received is {$receipt
                    ->amount}",
                "sale_id" =>  $type == "ORDINARY" ? $sale->id : null,
                "site_sale_id" =>  null,

            ]);

            return $receipt;
        });

        if ($type == "ORDINARY") {
            if ($sale->payables->count() > 0) {
                (new NotificationController())->notifyAccounts($sale, "payables");
            }
        }
    }

      private function getCodeReceiptNumber()
    {
        $last = Receipt::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }
}
