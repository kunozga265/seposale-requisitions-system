<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            "name" => "Thanthwe Cement (42.5)",
            "unit" => "50kg",
            "quantity" => 1,
            "cost" => 20000,
        ]);

        Product::create([
            "name" => "Akshar Cement",
            "unit" => "50 kg",
            "quantity" => 1,
            "cost" => 18000,
        ]);

        Product::create([
            "name" => "Quarry Stone",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
        ]);

        Product::create([
            "name" => "Quarry Stone",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
        ]);

        Product::create([
            "name" => "Quarry Stone",
            "unit" => "Tonne",
            "quantity" => 30,
            "cost" => 640000,
        ]);

        Product::create([
            "name" => "Pebble Stone",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
        ]);

        Product::create([
            "name" => "Pebble Stone",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
        ]);

        Product::create([
            "name" => "Pebble Stone",
            "unit" => "Tonne",
            "quantity" => 30,
            "cost" => 640000,
        ]);

        Product::create([
            "name" => "Quarry Dust",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 470000,
        ]);

        Product::create([
            "name" => "Quarry Dust",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
        ]);

        Product::create([
            "name" => "Quarry Dust",
            "unit" => "Tonne",
            "quantity" => 30,
            "cost" => 580000,
        ]);

        Product::create([
            "name" => "Cement Block (Medium)",
            "unit" => "150*200*400(mm)",
            "quantity" => 1,
            "cost" => 1300,
        ]);

        Product::create([
            "name" => "Cement Block (Big)",
            "unit" => "200*200*400(mm)",
            "quantity" => 1,
            "cost" => 1800,
        ]);

        Product::create([
            "name" => "River Sand",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 350000,
        ]);

        Product::create([
            "name" => "River Sand (Salima)",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 750000,
        ]);

    }
}
