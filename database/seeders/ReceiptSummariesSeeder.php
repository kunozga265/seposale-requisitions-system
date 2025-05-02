<?php

namespace Database\Seeders;

use App\Models\Receipt;
use App\Models\ReceiptSummary;
use App\Models\SiteSaleSummary;
use App\Models\Summary;
use Illuminate\Database\Seeder;

class ReceiptSummariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $receipts = Receipt::all();
        foreach ($receipts as $receipt) {
            if($receipt->information != null) {
                $information = json_decode($receipt->information);
                foreach ($information as $info) {
                     //create receipt transaction
                     $ReceiptSummary = ReceiptSummary::create([
                        "name" => $info->name,
                        "balance" => $info->balance,
                        "amount" => $info->amount,
                        "cost" => $info->cost ?? null,
                        "units" => $info->units ?? null,
                        "receipt_id" => $receipt->id,
                    ]);

                    if($receipt->sale != null){
                        $ReceiptSummary->update([
                            "summary_id" => $info->id,
                        ]);
                    }elseif ($receipt->siteSale !=null){
                        $ReceiptSummary->update([
                            "site_sale_summary_id" => $info->id,
                        ]);
                    }

                }
            }
        }
    }
}
