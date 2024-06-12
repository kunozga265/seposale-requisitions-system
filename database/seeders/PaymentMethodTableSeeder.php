<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create([
            "name" => "Cash"
        ]);
        PaymentMethod::create([
            "name" => "Airtel Money"
        ]);
        PaymentMethod::create([
            "name" => "TNM Mpamba"
        ]);
        PaymentMethod::create([
            "name" => "National Bank"
        ]);
        PaymentMethod::create([
            "name" => "Standard Bank"
        ]);
        PaymentMethod::create([
            "name" => "FCB"
        ]);
        PaymentMethod::create([
            "name" => "FDH"
        ]);
        PaymentMethod::create([
            "name" => "Ecobank"
        ]);
        PaymentMethod::create([
            "name" => "NBS"
        ]);
        PaymentMethod::create([
            "name" => "Centenary Bank"
        ]);
        PaymentMethod::create([
            "name" => "Other"
        ]);
        PaymentMethod::create([
            "name" => "CDH"
        ]);
    }
}
