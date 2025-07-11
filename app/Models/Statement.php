<?php

namespace App\Models;

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
                            if ($record->type = "CREDIT") {
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

    protected $fillable = [
        "serial",
        "name",
        "type",
        "active",
        "start_date",
        "end_date",
    ];
}
