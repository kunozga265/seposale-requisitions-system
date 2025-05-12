<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Receipt;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "reference" => "required",
            "from_to" => "required",
            "description" => "required",
            "total" => "required",
            "type" => "required",
            "account_id" => "required",
        ]);

        $account = Account::find($request->account_id);

        if (is_object($account)) {


            if ($request->type == "CREDIT") {
                $balance = $account->balance + $request->total;
            } else {
                $balance = $account->balance - $request->total;
            }

            Transaction::create([
                "date" => Carbon::now()->getTimestamp(),
                "reference" => strtoupper($request->reference),
                "description" => $request->description,
                "from_to" => $request->from_to,
                "expense_id" => null,
                "receipt_id" => null,
                "account_id" => $account->id,
                "total" => $request->total,
                "balance" => $balance,
                "type" => $request->type,
            ]);

            $account->update([
                "balance" => $balance,
            ]);

            return Redirect::back()->with("success", "Transaction recorded successfully");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Account not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Account not found');
            }
        }
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if (is_object($transaction)) {
            $request->validate([
                "reference" => "required",
                "from_to" => "required",
                "description" => "required",
                "total" => "required",
                "type" => "required",
            ]);
            $receipt = Receipt::where("code", $request->receipt_code)->first();
            $expense = Expense::where("code", $request->expense_code)->first();

            if ($transaction->type == "CREDIT") {
                $account_balance = $transaction->account->balance - $transaction->total;
                $transaction_balance = $transaction->balance - $transaction->total;
            } else {
                $account_balance = $transaction->account->balance + $transaction->total;
                $transaction_balance = $transaction->balance + $transaction->total;
            }

            if ($request->type == "CREDIT") {
                $account_balance += $request->total;
                $transaction_balance += $request->total;
            } else {
                $account_balance -= $request->total;
                $transaction_balance -= $request->total;
            }

            $transaction->account->update([
                "balance" => $account_balance,
            ]);

            //date
            if ($request->date != null && $request->date != "" && $request->date != 0) {
                $date = $request->date;
            } else {
                $date = $transaction->date;
            }

            $transaction->update([
                "date" => $date,
                "reference" => strtoupper($request->reference),
                "description" => $request->description,
                "from_to" => $request->from_to,
                "expense_id" => is_object($expense) ? $expense->id : null,
                "receipt_id" => is_object($receipt) ? $receipt->id : null,
                "total" => $request->total,
                // "balance" => $transaction_balance,
                "balance" => $request->balance,
                "type" => $request->type,
            ]);

            return Redirect::route("accounts.index")->with("success", "Transaction updated successfully");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Transaction not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Transaction not found');
            }
        }
    }

    public function destroy(Request $request, $id)
    {
        $transaction = Transaction::where('id', $id)->first();
        if (is_object($transaction)) {

            if ($transaction->type == "CREDIT") {
                $account_balance = $transaction->account->balance - $transaction->total;
            } else {
                $account_balance = $transaction->account->balance + $transaction->total;
            }

            $transaction->account->update([
                "balance" => $account_balance,
            ]);

            $transaction->delete();

            return Redirect::back()->with("success", "Transaction deleted successfully");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Transaction not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Transaction not found');
            }
        }
    }
}
