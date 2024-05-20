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
            "unit" => "Bag",
            "quantity" => 1,
            "cost" => 20000,
        ]);

        Product::create([
            "name" => "Akshar Cement",
            "unit" => "Bag",
            "quantity" => 1,
            "cost" => 18000,
        ]);

        Product::create([
            "name" => "Quarry Stone 20T",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
        ]);

        Product::create([
            "name" => "Quarry Stone 25T",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
        ]);

        Product::create([
            "name" => "Quarry Stone 30T",
            "unit" => "Tonne",
            "quantity" => 30,
            "cost" => 640000,
        ]);

        Product::create([
            "name" => "Pebble Stone 20T",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
        ]);

        Product::create([
            "name" => "Pebble Stone 25T",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
        ]);

        Product::create([
            "name" => "Pebble Stone 30T",
            "unit" => "Tonne",
            "quantity" => 30,
            "cost" => 640000,
        ]);

        Product::create([
            "name" => "Quarry Dust 20T",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 470000,
        ]);

        Product::create([
            "name" => "Quarry Dust 25T",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
        ]);

        Product::create([
            "name" => "Quarry Dust 30T",
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
            "name" => "River Sand 25T",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 350000,
        ]);

        Product::create([
            "name" => "River Sand 20T (Salima)",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 750000,
        ]);

    }
}
