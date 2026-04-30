<?php

namespace App\Models;

use App\Http\Controllers\AccountingAccountController;
use App\Http\Controllers\AppController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    use HasFactory;

    public function groups()
    {



        $types = AccountType::where("statement", $this->type)->get();
        $end_date = $this->end_date ?? Carbon::now()->getTimestamp();

        //record profits
        if ($this->type == "balance-sheet") {
            $this->recordProfits();
        }

        $types_data = [];
        foreach ($types as $type) {

            $group_data = [];
            $type_total = 0;
            foreach ($type->accountsGroups as $group) {
                $accounts_data = [];
                $group_total = 0;
                foreach ($group->accounts as $account) {

                    $sum = 0;

                    if ($this->type == 'income-statement') {

                        $records = $account->records()
                            ->where("date", ">=", $this->start_date)
                            ->where("date", "<=", $end_date)
                            ->get();

                        foreach ($records as $record) {
                            if ($record->type == "CREDIT") {
                                $sum += $record->amount;
                            } else {
                                $sum -= $record->amount;
                            }
                        }
                    } else {

                        $sum += $account->balance;
                    }


                    $sum = abs($sum);
                    if ($sum > 0) {
                        $accounts_data[] = [
                            "data" => $account,
                            "total" => $sum,
                            // "records" => $records
                        ];
                        $group_total += $sum;
                    }
                }

                if ($group_total > 0) {
                    $group_data[] = [
                        "data" => $group,
                        "total" => $group_total,
                        "accounts" => $accounts_data
                    ];
                }

                $type_total += $group_total;
            }

            if ($type_total > 0) {
                $types_data[] = [
                    "data" => $type,
                    "groups" => $group_data,
                ];
            }
        }

        return $types_data;
    }

    public function recordProfits()
    {
        $types = AccountType::where("statement", "income-statement")->get();
        $retained_earnings_account = (new AccountingAccountController())->getAccount(3020);
        $last_record = $retained_earnings_account->records()->orderBy("created_at", "desc")->first();
        $timestamp = is_object($last_record) ? $last_record->created_at->getTimestamp() : 0;
        $start_date = Carbon::createFromTimestamp($timestamp);

        $profits = 0;
        foreach ($types as $type) {
            foreach ($type->accountsGroups as $group) {
                foreach ($group->accounts as $account) {

                    $records = $account->records()
                        ->where("created_at", ">", $start_date)
                        ->get();

                    foreach ($records as $record) {
                        if ($record->amount > 0) {
                        
                            if ($record->type == "CREDIT") {
                                $profits += $record->amount;
                            } else {
                                $profits -= $record->amount;
                            }
                        }
                    }
                }
            }
        }

        // dd($profits);

        if ($profits != 0) {
            $isProfit = $profits > 0;
            $now = Carbon::now()->getTimestamp();
            if ($timestamp != 0) {
                $date = date("Y-M-d", $timestamp) . " to " . date("Y-M-d", $now);
            } else {
                $date = date("Y-M-d", $now);
            }

            AccountingRecord::create([
                "serial" => (new AppController())->generateUniqueCode("ACCOUNTING"),
                "reference" => strtoupper(""),
                "date" => $now,
                "name" => $isProfit ? "PROFIT RECORD" : "LOSS RECORD",
                "description" => "[PERIOD: $date]",
                "amount" => abs($profits),
                "opening_balance" => $retained_earnings_account->balance,
                "closing_balance" => $retained_earnings_account->balance + $profits,
                "type" => $isProfit ? "CREDIT" : "DEBIT", // incrementing the account balance
                "accounting_account_id" => $retained_earnings_account->id,
            ]);

            $retained_earnings_account->update([
                "balance" => $retained_earnings_account->balance + $profits,
            ]);
        }
    }

    protected $fillable = [
        "serial",
        "name",
        "type",
        "active",
        "start_date",
        "end_date",
    ];
}
