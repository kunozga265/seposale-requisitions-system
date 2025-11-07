<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryResource;
use App\Http\Resources\PayableResource;
use App\Http\Resources\RequestFormResource;
use App\Models\Payable;
use App\Models\RequestForm;
use App\Models\AccountingAccount;
use App\Models\AccountingRecord;
use App\Models\RequestFormItem;
use App\Models\CreditVoucher;
use App\Models\SupplierVoucher;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class PayableController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Payables/Index', $this->getPayables());
    }

    public function storeFromRequisition(Request $request, $id)
    {
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {
            $request->validate([
                'items' => 'required',
            ]);

            $accounts_payable_account = (new AccountingAccountController())->getAccount(2010);
            $accounts_payable_balance = $accounts_payable_account->balance;

            $filtered_transactions = [];

            foreach ($request->items as $item) {
                if ($item["amount"] > 0) {
                    $filtered_transactions[] = $item;
                }
            }

            foreach ($filtered_transactions as $item) {
                if ($item["transporterId"] == null && $item["supplierId"] == null) {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "{$item['details']} needs to be attached to either transporter or supplier to record payable"], 404);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', "{$item['details']} needs to be attached to either transporter or supplier to record payable");
                    }
                }
            }

            $grouped = array_reduce($filtered_transactions, function ($carry, $item) {
                $carry[$item['accountId']][] = $item;
                return $carry;
            }, []);

            $index = 0;
            foreach ($grouped as $items) {
                $alternative_account = AccountingAccount::find($items[0]["accountId"]);
                $alternative_account_balance = $alternative_account->balance;

                foreach ($items as $item) {
                    $request_form_item = RequestFormItem::find($item["id"]);
                    $request_form_item_balance = $request_form_item->balance - $item["amount"];
                    $request_form_item->update([
                        "balance" => $request_form_item_balance,
                        "status" => $request_form_item_balance == 0 ? 2 : 1 // Mark as paid if balance is zero or less
                    ]);


                    $main_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper($request->reference),
                        "date" => $item["date"] + $index,
                        "name" => "Requisition #{$requestForm->formattedCode()} - " . $item["name"],
                        "description" => $item["details"],
                        "amount" => $item["amount"],
                        "opening_balance" => $accounts_payable_balance,
                        "closing_balance" => $accounts_payable_balance + $item["amount"],
                        "type" => "CREDIT", // increasing the account balance
                        "accounting_account_id" => $accounts_payable_account->id,
                        "request_form_item_id" => $request_form_item->id,
                        "accounting_record_id" => null, // This will be updated later
                    ]);
                    $accounts_payable_balance += $item["amount"];

                    $alternate_record = AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper($request->reference),
                        "date" => $item["date"] + $index,
                        "name" => "Requisition #{$requestForm->formattedCode()} - " . $item["name"],
                        "description" => $item["details"],
                        "amount" => $item["amount"],
                        "opening_balance" => $alternative_account_balance,
                        "closing_balance" => $alternative_account_balance + $item["amount"],
                        "type" => "DEBIT",
                        "accounting_account_id" => $alternative_account->id,
                        "accounting_record_id" => $main_record->id,
                        "request_form_item_id" => $request_form_item->id,
                    ]);
                    $alternative_account_balance += $item["amount"];

                    $main_record->update([
                        "accounting_record_id" => $alternate_record->id
                    ]);

                    $index++;

                    $payable = Payable::create([
                        "code" => (new PayableController())->getCodeNumber(),
                        'serial' => (new AppController())->generateUniqueCode("PAYABLE"),
                        "description" => $item["details"],
                        "total" => $item["amount"],
                        "date" => $item["date"] + $index,
                        "contents" => json_encode([]),
                        "account_id" => $alternative_account->id,
                        "transporter_id" => $item["transporterId"],
                        "supplier_id" => $item["supplierId"],
                        "delivery_id" => $requestForm->delivery?->id,
                        "sale_id" => $requestForm->delivery?->summary->sale->id,
                        // "request_id" => $requestForm->id,
                        "paid" => false,
                    ]);

                    if ($requestForm->type == 'INVENTORY') {
                        $request_form_item->update([
                            "transporter_id" => $item["transporterId"],
                            "supplier_id" => $item["supplierId"],
                        ]);
                    }

                    if ($requestForm->type == 'OPERATIONS') {
                        CreditVoucher::create([
                            'serial' => (new AppController())->generateUniqueCode("CREDIT_VOUCHER"),
                            "code" => (new CreditVoucherController())->getCodeNumber(),
                            "date" => Carbon::now()->getTimestamp(),
                            "amount" => $item["amount"],
                            "balance" => $item["amount"],
                            "payable_id" => $payable->id,
                            "transporter_id" => $item["transporterId"],
                            "supplier_id" => $item["supplierId"],
                            "delivery_id" => $requestForm->delivery?->id,
                            "sale_id" => $requestForm->delivery?->summary->sale->id,
                            "paid" => false,
                            "request_id" => $requestForm->id,
                            "request_form_item" => $request_form_item->id,

                        ]);
                    } else if ($requestForm->type == 'INVENTORY') {
                         SupplierVoucher::create([
                            'serial' => (new AppController())->generateUniqueCode("SUPPLY_VOUCHER"),
                            "code" => (new SupplierVoucherController())->getCodeNumber(),
                            "date" => Carbon::now()->getTimestamp(),
                            "amount" => $item["amount"],
                            // "balance" => $item["amount"],
                            "payable_id" => $payable->id,
                            "transporter_id" => $item["transporterId"],
                            "supplier_id" => $item["supplierId"],
                            "site_id" => $requestForm->site_id,
                            "paid" => false,
                            "request_id" => $requestForm->id,
                            "request_form_item" => $request_form_item->id,

                        ]);
                        
                    }
                }

                //Update the account balance
                $alternative_account->update([
                    "balance" => $alternative_account_balance
                ]);
            }

            //Update the account balance
            $accounts_payable_account->update([
                "balance" => $accounts_payable_balance
            ]);

            if ($requestForm->type == 'OPERATIONS') {
                (new NotificationController())->notifyCreditors($requestForm);
            } else if ($requestForm->type == 'INVENTORY') {
                (new NotificationController())->notifySupplier($requestForm);
            }

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "Payables have been recorded",
                "request_form_id" => $requestForm->id,
            ]);

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Payables have been recorded"], 200);
            } else {
                //Web Response
                return Redirect::back()->with('success', 'Payables have been recorded');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Request form not found');
            }
        }
    }

    public function getPayables()
    {
        $payables = Payable::where("paid", 0)->orderBy("date", "asc")->get();
        $suppliers = [];
        $transporters = [];

        foreach ($payables as $payable) {
            if ($payable->transporter != null) {
                $transporters[] = $payable;
            } else if ($payable->supplier != null) {
                $suppliers[] = $payable;
            }
        }

        //        $suppliers = $this->convertToArray($suppliers);
        //        $transporters = $this->convertToArray($transporters);


        $groupedTransporters = array_reduce($transporters, function ($carry, $item) {
            $carry[$item['transporter_id']][] = $this->convertToArray($item);
            return $carry;
        }, []);

        $groupedSuppliers = array_reduce($suppliers, function ($carry, $item) {
            $carry[$item['supplier_id']][] = $this->convertToArray($item);
            return $carry;
        }, []);

        $all = [];
        $total = 0;

        foreach ($groupedTransporters as $groupedTransporter) {
            $sum = 0;
            $name = $groupedTransporter[0]["payee"];
            $items = [];
            foreach ($groupedTransporter as $item) {
                $items[] = $item;
                $sum += $item["total"];
            }
            $all[] = [
                "name" => $name,
                "category" => "Transporter",
                "items" => $items,
                "total" => $sum,
            ];
            $total += $sum;
        }

        foreach ($groupedSuppliers as $groupedSupplier) {
            $sum = 0;
            $name = $groupedSupplier[0]["payee"];
            $items = [];
            foreach ($groupedSupplier as $item) {
                $items[] = $item;
                $sum += $item["total"];
            }
            $all[] = [
                "name" => $name,
                "category" => "Supplier",
                "items" => $items,
                "total" => $sum,
            ];
            $total += $sum;
        }

        usort($all, function ($a, $b) {
            if ($a['total'] < $b['total']) {
                return 1;
            } elseif ($a['total'] > $b['total']) {
                return -1;
            }
            return 0;
        });

        return [
            "transporters" => $groupedTransporters,
            "suppliers" => $groupedSuppliers,
            "all" => $all,
            "total" => $total,
        ];
    }

    private function convertToArray($arr)
    {
        return [
            "checked"               => false,
            "id"                    => $arr->id,
            "code"                  => $arr->formattedCode(),
            "payee"                 => $arr->getName(),
            "description"           => trim($arr->description),
            "total"                 => $arr->total,
            "date"                  => $arr->date,
            "contents"              => json_decode($arr->contents),
            "expenseType"           => $arr->expenseType,
            "sale"                  => $arr->sale,
            "delivery"              => $arr->delivery != null ? new DeliveryResource($arr->delivery) : null,
            "requestForm"           => $arr->requestForm != null ? new RequestFormResource($arr->requestForm) : null,
            "transporter"           => $arr->transporter,
            "supplier"              => $arr->supplier,
        ];
    }

    public function getCodeNumber()
    {
        $last = Payable::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }
}
