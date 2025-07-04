<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountsGroupResource;
use App\Http\Resources\AccountTypeResource;
use App\Http\Resources\SiteResource;
use App\Models\AccountingAccount;
use App\Models\AccountsGroup;
use App\Models\AccountType;
use App\Models\Inventory;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function accountingCentre()
    {
        $sites = Site::orderBy("name", "asc")->get();
        // Return the settings view
        return Inertia::render('Settings/AccountingCentre', [
            "sites" => $sites
        ]);
    }
    public function balanceSheet()
    {
        $account_types = AccountType::where('statement', 'balance-sheet')
            ->get();

        //
        return Inertia::render('Settings/Statements', [
            "accountTypes" => AccountTypeResource::collection($account_types),
        ]);
    }
    public function incomeStatement()
    {
        $account_types = AccountType::where('statement', 'income-statement')
            ->get();

        //
        return Inertia::render('Settings/Statements', [
            "accountTypes" => AccountTypeResource::collection($account_types),
        ]);
    }

    public function site(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            $accounts = AccountingAccount::orderBy("code", "asc")->get();

            return Inertia::render('Settings/Site', [
                "site" => new SiteResource($site),
                "accounts" => $accounts,
            ]);
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
    }

    public function siteAttachAccount(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {

            $request->validate([
                // "wallet_id" => "required"
            ]);

            if (isset($request->wallet_id)) {
                $wallet_account = AccountingAccount::find($request->wallet_id);

                if (!$site->accounts()->where("accounting_account_id", $wallet_account->id)->exists()) {
                    //delete other wallet accounts
                    $existing_accounts = $site->accounts()->where("special_type", "WALLET")->get();
                    foreach ($existing_accounts as $account) {
                        $site->accounts()->detach($account);
                    }

                    //attach new wallet account
                    $site->accounts()->attach($wallet_account);
                }
            }

            if (isset($request->cogs_id)) {
                $cogs_account = AccountingAccount::find($request->cogs_id);

                if (!$site->accounts()->where("accounting_account_id", $cogs_account->id)->exists()) {
                    //delete other wallet accounts
                    $existing_accounts = $site->accounts()->where("special_type", "COGS-DIRECT-OSS")->get();
                    foreach ($existing_accounts as $account) {
                        $site->accounts()->detach($account);
                    }

                    //attach new wallet account
                    $site->accounts()->attach($cogs_account);
                }
            }

            return Redirect::back()->with("success", "Successfully attached account");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
    }

    public function inventoryAttachAccount(Request $request, $id)
    {
        $inventory = Inventory::find($id);

        if (is_object($inventory)) {

            $request->validate([
                "inventory_id" => "required",
                "cogs_id" => "required",
                "revenue_id" => "required",
            ]);

            // dd($request->all());

            $inventory->update([
                "inventory_account_id" => $request->inventory_id,
                "cogs_account_id" => $request->cogs_id,
                "revenue_account_id" => $request->revenue_id,
            ]);

            return Redirect::back()->with("success", "Successfully updated {$inventory->name} accounts");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Inventory item not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Inventory item not found');
            }
        }
    }
}
