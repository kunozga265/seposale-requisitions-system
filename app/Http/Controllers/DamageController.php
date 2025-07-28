<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use App\Models\Damage;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DamageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // 'total' => ['required', "numeric", "gt:0"],
            'quantity' => ['required', "numeric", "gt:0"],
            'inventory_id' => ['required'],
            'date' => ['required'],
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);
        $quantity = $request->quantity;
        $date = $request->date;
        $cost = 0;

        //check if damages can be recorded
        if ($inventory->stock() < $quantity) {
            return Redirect::back()->with("error", "{$inventory->name} is out of stock cannot record damages");
        } else if (!$inventory->producible) {
            return Redirect::back()->with("error", "Cannot record damages on {$inventory->name}");
        }

        do {
            $batch = $inventory->batches()->where("accounting_balance", ">", 0)->orderBy("date", "asc")->first();
            $count = 0;
            if (is_object($batch)) {
                //check if batch quantity is greater
                if ($batch->accounting_balance >= $quantity) {
                    $count = $quantity;
                    $balance = $batch->accounting_balance - $quantity;
                }
                //this branch if batch balance is lower
                else {
                    $count = $batch->accounting_balance;
                    $balance = 0;
                }

                //update the balance
                $batch->update([
                    "accounting_balance" => $balance,
                ]);

                $damages_cost = $batch->price * $count;
                //create a record of the transaction
                if ($damages_cost > 0 && $count > 0) {
                    Damage::create([
                        "date" => $date,
                        "batch_id" => $batch->id,
                        "inventory_id" => $inventory->id,
                        "quantity" => $count,
                        "cost" => $damages_cost,
                    ]);
                } else {
                    // return Redirect::back()->with("error", "{$inventory->name} is out of stock. Some damages not recorded. Record manually.");
                    break;
                }
            } else {
                // return Redirect::back()->with("error", "{$inventory->name} is out of stock");
                break;
            }
            $cost += $damages_cost;
            $quantity -= $count;
        } while ($quantity > 0);


        $quantity = $request->quantity;
        do {
            $batch = $inventory->batches()->where("balance", ">", 0)->orderBy("date", "asc")->first();
            $count = 0;
            if (is_object($batch)) {
                //check if batch quantity is greater
                if ($batch->balance >= $quantity) {
                    $count = $quantity;
                    $balance = $batch->balance - $quantity;
                }
                //this branch if batch balance is lower
                else {
                    $count = $batch->balance;
                    $balance = 0;
                }

                //update the balance
                $batch->update([
                    "balance" => $balance,
                ]);
            } else {
                break;
            }

            $quantity -= $count;
        } while ($quantity > 0);

        //update the balance
        $inventory->update([
            'available_stock' => $inventory->available_stock - $request->quantity
        ]);


        //record accounts
        $inventory_account = $inventory->inventoryAccount;
        $inventory_account_balance = $inventory->inventoryAccount->balance;
        $damages_cost = $cost;

        $operating_expenses_account = AccountingAccount::where("code", 6050)->first();
        $operating_expenses_record = $operating_expenses_account->records()->create([
            "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
            "reference" => strtoupper(""),
            "date" => Carbon::now()->getTimestamp(),
            "name" => "Damages Record",
            "description" => "{$inventory->name} Damages - " . $inventory->formattedUnits($request->quantity),
            "amount" => $damages_cost,
            "opening_balance" => $operating_expenses_account->balance,
            "closing_balance" => $operating_expenses_account->balance + $damages_cost,
            "type" => "DEBIT", // incrementing the account balance
            "accounting_account_id" => $inventory_account->id,
        ]);
        $operating_expenses_account->update([
            "balance" => $operating_expenses_account->balance + $damages_cost
        ]);

        //decrease inventory
        $inventory_record = $inventory_account->records()->create([
            "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
            "reference" => strtoupper(""),
            "date" => Carbon::now()->getTimestamp(),
            "name" => "Damages Record",
            "description" => "{$inventory->name} Damages - " . $inventory->formattedUnits($request->quantity),
            "amount" => $damages_cost,
            "opening_balance" => $inventory_account_balance,
            "closing_balance" => $inventory_account_balance - $damages_cost,
            "type" => "CREDIT", // incrementing the account balance
            "accounting_account_id" => $inventory_account->id,
            "accounting_record_id" => $operating_expenses_record->id,

        ]);

        $operating_expenses_record->update([
            "accounting_record_id" => $inventory_record->id,
        ]);

        $inventory_account_balance -= $damages_cost;

        $inventory_account->update([
            "balance" => $inventory_account_balance
        ]);

        return Redirect::back()->with("success","Damages recorded successfully!");
    }
}
