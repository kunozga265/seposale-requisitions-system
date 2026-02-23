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
        PaymentMethod::updateOrCreate(
            [
                "name" => "Cash"
            ],
            ["photo" => "files/payment-methods/cash.png"]

        );
        PaymentMethod::updateOrCreate(
            ["name" => "Airtel Money"],
            ["photo" => "files/payment-methods/airtel_money.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "TNM Mpamba"],
            ["photo" => "files/payment-methods/tnm_mpamba.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "National Bank"],
            ["photo" => "files/payment-methods/nb.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "Standard Bank"],
            ["photo" => "files/payment-methods/std.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "FCB"],
            ["photo" => "files/payment-methods/fcb.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "FDH"],
            ["photo" => "files/payment-methods/fdh.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "Ecobank"],
            ["photo" => "files/payment-methods/ecobank.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "NBS"],
            ["photo" => "files/payment-methods/nbs.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "Centenary Bank"],
            ["photo" => "files/payment-methods/cb.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "Other"],
            ["photo" => "files/payment-methods/other.jpeg"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "CDH"],
            ["photo" => "files/payment-methods/cdh.png"]
        );
        PaymentMethod::updateOrCreate(
            ["name" => "Withholding"],
            ["photo" => "files/payment-methods/mra.png"]
        );
    }
}
