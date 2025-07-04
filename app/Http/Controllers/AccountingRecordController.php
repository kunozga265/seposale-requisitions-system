<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use App\Models\AccountingRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountingRecordController extends Controller
{
    public function reverseTransactions($raw_records, $production_id = null, $collection_id = null){
         $records = [];
            foreach ($raw_records as $record) {
                $records[] = $record->toArray();
            }
            $groupRecords = array_reduce($records, function ($carry, $item) {
                $carry[$item['accounting_account_id']][] = $item;
                return $carry;
            }, []);

            $index = 0;
            foreach ($groupRecords as $records) {
                $account = AccountingAccount::find($records[0]["accounting_account_id"]);
                $account_balance = $account->balance;
                foreach ($records as $record) {
                    $opening_balance = $account_balance;
                    //check account type
                    if ($account->type == "DEBIT") {
                        //if acccount is inherently a debit account and the record is a debit
                        if ($record["type"] == "DEBIT") {
                            $type = "CREDIT";
                            //reversal is subtracting the account
                            $account_balance -= $record["amount"];
                        } else {
                            $type = "DEBIT";
                            //reversal is adding to the account
                            $account_balance += $record["amount"];
                        }
                    } else {
                        if ($record["type"] == "DEBIT") {
                            $type = "CREDIT";
                            //reversal is adding to the account
                            $account_balance += $record["amount"];
                        } else {
                            $type = "DEBIT";
                            //reversal is subtracting the account
                            $account_balance -= $record["amount"];
                        }
                    }

                    AccountingRecord::create([
                        "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                        "reference" => strtoupper(""),
                        "date" => Carbon::now()->getTimestamp() + $index,
                        "name" => "!!REVERSAL TRANSACTION!! " . $record["name"],
                        "description" => "!!REVERSAL TRANSACTION!! " . $record["description"],
                        "amount" => $record["amount"],
                        "opening_balance" => $opening_balance,
                        "closing_balance" => $account_balance,
                        "type" => $type,
                        "accounting_account_id" => $account->id,
                        "production_id" => $production_id,
                        "collection_id" => $collection_id,
                    ]);

                    $index++;
                }

                $account->update([
                    "balance" => $account_balance
                ]);
            }

    }
}
