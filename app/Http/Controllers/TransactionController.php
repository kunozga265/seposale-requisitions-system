<?php

namespace App\Http\Controllers;

use App\Models\Account;
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


            if ($request->type) {
                $balance = $account->balance + $request->total;
            }else{
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

            return Redirect::back()->with("success","Transaction recorded successfully");

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
}
