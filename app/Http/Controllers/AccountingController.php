<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountingAccountResource;
use App\Models\AccountingAccount;
use Illuminate\Http\Request;
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
  
}
