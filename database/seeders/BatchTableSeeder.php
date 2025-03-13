<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\SystemLog;
use Illuminate\Database\Seeder;

class BatchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $logs = SystemLog::where("inventory_id", "!=",null)->get();
        foreach ($logs as $log){
            $contents = json_decode($log->contents);

            Batch::create([
                "date" => $contents->date,
                "price" => 0,
                "quantity" => $contents->quantity,
                "balance" => 0,
                "photo" => $contents->photo ?? null,
                "comments" => $contents->comments,
                "inventory_id" => $log->inventory_id,
                "user_id" => $log->user_id,
            ]);
        }
    }
}
