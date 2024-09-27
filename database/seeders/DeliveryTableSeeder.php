<?php

namespace Database\Seeders;

use App\Http\Controllers\DeliveryController;
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
        $deliveries = Delivery::all();

        foreach ($deliveries as $delivery){
            if($delivery->status > 0){
                $delivery->update([
                   "code" => (new DeliveryController())->getCodeNumber()
                ]);
            }
            $delivery->update([
                "tracking_number" => uniqid()
            ]);
        }
    }
}
