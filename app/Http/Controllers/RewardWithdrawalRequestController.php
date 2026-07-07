<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use App\Models\Client;
use App\Models\ClientReward;
use App\Models\RewardWithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RewardWithdrawalRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = RewardWithdrawalRequest::with(['client', 'approvedBy', 'paidBy', 'paymentMethod'])
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'paid')")
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($r) {
                return [
                    'id'            => $r->id,
                    'client'        => ['id' => $r->client?->id, 'name' => $r->client?->name],
                    'amount'        => (float) $r->amount,
                    'status'        => $r->status,
                    'notes'         => $r->notes,
                    'paymentMethod' => $r->paymentMethod ? $r->paymentMethod->name : null,
                    'payoutDetails' => $r->payout_details ?? [],
                    'approvedBy'    => $r->approvedBy?->name,
                    'approvedAt'    => $r->approved_at?->toDateTimeString(),
                    'paidBy'        => $r->paidBy?->name,
                    'paidAt'        => $r->paid_at?->toDateTimeString(),
                    'createdAt'     => $r->created_at->toDateTimeString(),
                ];
            });

        $clients = Client::orderBy('name')->get(['id', 'name']);

        $walletAccounts = AccountingAccount::whereBetween('code', [1010, 1029])
            ->orderBy('code')
            ->get(['id', 'name', 'code', 'balance']);

        return Inertia::render('Rewards/Index', [
            'withdrawalRequests' => $requests,
            'clients'            => $clients,
            'walletAccounts'     => $walletAccounts,
        ]);
    }

    public function approve($id)
    {
        $req = RewardWithdrawalRequest::findOrFail($id);
        $req->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return Redirect::back()->with('success', 'Withdrawal request approved.');
    }

    public function reject($id)
    {
        RewardWithdrawalRequest::findOrFail($id)->update(['status' => 'rejected']);
        return Redirect::back()->with('success', 'Withdrawal request rejected.');
    }

    public function pay($id)
    {
        $req = RewardWithdrawalRequest::findOrFail($id);

        $req->update([
            'status'  => 'paid',
            'paid_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        ClientReward::create([
            'client_id'                    => $req->client_id,
            'reward_withdrawal_request_id' => $req->id,
            'amount'                       => -abs($req->amount),
            'type'                         => 'withdrawn',
            'date'                         => now()->timestamp,
        ]);

        return Redirect::back()->with('success', 'Marked as paid and balance deducted.');
    }
}
