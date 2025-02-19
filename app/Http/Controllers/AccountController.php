<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $clients = Account::orderBy("name", "asc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(AccountResource::collection($clients));
        else {
            //Web Response
            return Inertia::render('Accounts/Index', [
                'accounts' => AccountResource::collection($clients),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $account = Account::find($id);
        $accounts = Account::where("id", "!=", $id)->get();

        if (is_object($account)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new AccountResource($account));
            } else {
                //Web Response
                return Inertia::render('Accounts/Show', [
                    'account' => new AccountResource($account),
                    'accounts' => $accounts
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Account not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Account not found');
            }
        }
    }

    public function transfer(Request $request, $id)
    {
        $request->validate([
            "account_id" => "required",
            "amount" => "required"
        ]);

        $accountFrom = Account::find($id);


        if (is_object($accountFrom)) {

            $balanceFrom = $accountFrom->balance - $request->amount;
            $accountFrom->update([
                "balance" => $balanceFrom
            ]);

            $accountTo = Account::find($request->account_id);
            $balanceTo = $accountTo->balance + $request->amount;
            $accountTo->update([
                "balance" => $balanceTo
            ]);

            //Debit
            Transaction::create([
                "date" => Carbon::now()->getTimestamp(),
                "reference" => $request->reference_from,
                "description" => "Funds Transfer",
                "from_to" => $accountTo->name,
                "expense_id" => null,
                "receipt_id" => null,
                "account_id" => $accountFrom->id,
                "total" => $request->amount,
                "balance" => $balanceFrom,
                "type" => "DEBIT",
            ]);

            //Credit
            Transaction::create([
                "date" => Carbon::now()->getTimestamp(),
                "reference" => $request->reference_to,
                "description" => "Funds Transfer",
                "from_to" => $accountFrom->name,
                "expense_id" => null,
                "receipt_id" => null,
                "account_id" => $accountTo->id,
                "total" => $request->amount,
                "balance" => $balanceTo,
                "type" => "CREDIT",
            ]);

            if (isset($request->fee) && $request->fee != 0) {
                $expense = Expense::create([
                    "code" => (new ExpenseController())->getCodeNumber(),
                    "description" => "Interbank Transfer Fee",
                    "total" => $request->fee,
                    "date" => Carbon::now()->getTimestamp(),
                    "contents" => json_encode([]),
                    "expense_type_id" => env('BANK_CHARGES_EXPENSE_TYPE_ID'),
                    "request_id" => null,
                    "transporter_id" => null,
                    "supplier_id" => null,
                    "delivery_id" => null,
                    "sale_id" => null,
                    "account_id" => $accountFrom->id,
                    "reference" => $request->reference_fee,
                ]);

                Transaction::create([
                    "date" => Carbon::now()->getTimestamp(),
                    "reference" => $request->reference_fee,
                    "description" => "Funds Transfer",
                    "from_to" => $accountTo->name,
                    "expense_id" => null,
                    "receipt_id" => null,
                    "account_id" => $accountFrom->id,
                    "total" => $request->fee,
                    "balance" => $balanceFrom,
                    "type" => "DEBIT",
                ]);
            }
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new AccountResource($accountFrom));
            } else {
                //Web Response
                return Redirect::back()->with("success","Successfully recorded the transactions");
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Account not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Account not found');
            }
        }
    }

}
