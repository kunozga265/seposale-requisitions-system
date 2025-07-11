<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountingAccountResource;
use App\Http\Resources\AccountingRecordResource;
use App\Models\AccountingAccount;
use App\Models\AccountingRecord;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AccountingController extends Controller
{
    public function index()
    {
        $accountingAccounts = AccountingAccount::orderBy('code', 'asc')
            ->get();
        // Return the accounting dashboard view
        return Inertia::render('Accounting/Index', [
            'accounts' => AccountingAccountResource::collection($accountingAccounts),

        ]);
    }
    

    public function show($code)
    {
        // Find the accounting account by code
        $account = AccountingAccount::where('code', $code)->firstOrFail();

        // Load the related records
        $account->load('records');

        // If the account is not found, return a 404 response
        if (!$account) {
            abort(404, 'Accounting account not found');
        }

        // Return the accounting account details view
        return Inertia::render('Accounting/Show', [
            'account' => new AccountingAccountResource($account),
        ]);
    }

    public function showRecord($serial)
    {
        // Find the accounting account by code
        $record = AccountingRecord::where('serial', $serial)->firstOrFail();

        // If the account is not found, return a 404 response
        if (!$record) {
            abort(404, 'Accounting record not found');
        }

        // Return the accounting account details view
        return Inertia::render('Accounting/Record', [
            'record' => new AccountingRecordResource($record),
        ]);
    }

    public function addTransaction(Request $request)
    {
        $request->validate([
            "amount" => "required",
            "description" => "required",
            "debit_account_id" => "required",
            "credit_account_id" => "required",
        ]);

        $debit_account = AccountingAccount::find($request->debit_account_id);
        $credit_account = AccountingAccount::find($request->credit_account_id);
        $amount = $request->amount;

        if (is_object($debit_account) && is_object($credit_account)) {
            $debit_record = AccountingRecord::create([
                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                "reference" => strtoupper($request->transfer_debit_account_reference),
                "date" => Carbon::now()->getTimestamp(),
                "name" => $credit_account->name,
                "description" => $request->description,
                "amount" => $amount,
                "opening_balance" => $debit_account->balance,
                "closing_balance" => $debit_account->balance + $amount,
                "type" => "DEBIT", // decreasing the account balance
                "accounting_account_id" => $debit_account->id,
                "accounting_record_id" => null, // This will be updated later
            ]);


            $credit_record = AccountingRecord::create([
                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                "reference" => strtoupper($request->transfer_credit_account_reference),
                "date" => Carbon::now()->getTimestamp(),
                "name" => $debit_account->name,
                "description" => $request->description,
                "amount" => $amount,
                "opening_balance" => $credit_account->balance,
                "closing_balance" => $credit_account->balance - $amount,
                "type" => "CREDIT",
                "accounting_account_id" => $credit_account->id,
                "accounting_record_id" => $debit_record->id,

            ]);

            $debit_record->update([
                "accounting_record_id" => $credit_record->id
            ]);

            //update balances
            $debit_account->update([
                "balance" => $debit_account->balance + $amount
            ]);

            $credit_account->update([
                "balance" => $credit_account->balance - $amount
            ]);

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "Transferred {$amount} from {$credit_account->name} to {$debit_account->name}",
            ]);

          //Web Response
                return Redirect::back()->with("success", "Successfully transferred the funds");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Invalid account entered"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Invalid account entered');
            }
        }
    }

   
}
