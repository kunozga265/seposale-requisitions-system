<?php

namespace Database\Seeders;

use App\Models\Summary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SummaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $summaries = Summary::all();

        foreach ($summaries as $summary){
            if ($summary->product_id > 6){
                $summary->update([
                    //set to other product
                    "product_id" => 7,
                ]);
            }

            $variant = $summary->variant;
            Log::info($variant);
            $summary->update([
                "description" => $summary->fullName(),
                "units" => $variant->unit,
            ]);
        }
    }
}
