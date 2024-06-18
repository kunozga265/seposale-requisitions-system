<?php

namespace Database\Seeders;

use App\Models\Delivery;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class DeliveryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sales = Sale::all();

        foreach ($sales as $sale){
            Delivery::create([
                "status"=>0,
                "sale_id"=>$sale->id
            ]);
        }
    }
}
