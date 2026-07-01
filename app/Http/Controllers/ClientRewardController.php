<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use App\Models\AccountingRecord;
use App\Models\Client;
use App\Models\ClientReward;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ClientRewardController extends Controller
{
    // Advertising & Promotion Expenses account code — debit this when a reward is granted.
    private const PROMOTION_ACCOUNT_CODE = 6010;

    public function store(Request $request)
    {
        $request->validate([
            'client_id'          => 'required|integer|exists:clients,id',
            'amount'             => 'required|numeric|min:1',
            'wallet_account_id'  => 'required|integer|exists:accounting_accounts,id',
            'description'        => 'nullable|string|max:255',
        ]);

        $client = Client::findOrFail($request->client_id);

        DB::transaction(function () use ($request, $client) {
            // 1. Credit the client's reward wallet.
            ClientReward::create([
                'client_id' => $client->id,
                'amount'    => $request->amount,
                'type'      => 'earned',
                'date'      => now()->timestamp,
            ]);

            $description = $request->description
                ? $request->description
                : "Manual reward for {$client->name}";

            $wallet  = AccountingAccount::findOrFail($request->wallet_account_id);
            $expense = AccountingAccount::where('code', self::PROMOTION_ACCOUNT_CODE)->firstOrFail();

            $serial = (new AppController())->generateUniqueCode('ACCOUNTING');

            // 2a. DEBIT Advertising & Promotion Expenses (expense increases).
            $expenseRecord = AccountingRecord::create([
                'serial'               => $serial,
                'reference'            => '',
                'date'                 => Carbon::now()->getTimestamp(),
                'name'                 => $client->name,
                'description'          => $description,
                'amount'               => $request->amount,
                'opening_balance'      => $expense->balance,
                'closing_balance'      => $expense->balance + $request->amount,
                'type'                 => 'DEBIT',
                'accounting_account_id' => $expense->id,
            ]);

            $expense->update(['balance' => $expense->balance + $request->amount]);

            // 2b. CREDIT the selected wallet account (funds flow out).
            $walletRecord = AccountingRecord::create([
                'serial'               => (new AppController())->generateUniqueCode('ACCOUNTING'),
                'reference'            => '',
                'date'                 => Carbon::now()->getTimestamp(),
                'name'                 => $client->name,
                'description'          => $description,
                'amount'               => $request->amount,
                'opening_balance'      => $wallet->balance,
                'closing_balance'      => $wallet->balance - $request->amount,
                'type'                 => 'CREDIT',
                'accounting_account_id' => $wallet->id,
                'accounting_record_id'  => $expenseRecord->id,
            ]);

            $wallet->update(['balance' => $wallet->balance - $request->amount]);

            $expenseRecord->update(['accounting_record_id' => $walletRecord->id]);
        });

        return Redirect::back()->with('success', "Reward of MWK " . number_format($request->amount, 2) . " granted to {$client->name}.");
    }
}
