<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
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
        $product = Product::create([
            "name" => "Cement",

        ]);

        ProductVariant::create([
            "description" => "Thanthwe (42.5)",
            "unit" => "50kg",
            "quantity" => 1,
            "cost" => 20000,
            "product_id"=>$product->id
        ]);

        ProductVariant::create([
            "description" => "Akshar (32.5)",
            "unit" => "50 kg",
            "quantity" => 1,
            "cost" => 18000,
            "product_id"=>$product->id
        ]);

        $product = Product::create([
            "name" => "Quarry Stone",
        ]);

        ProductVariant::create([
            "description" => "20 Tonnes",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
            "product_id"=>$product->id
        ]);

        ProductVariant::create([
            "description" => "25 Tonnes",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 575000,
            "product_id"=>$product->id
        ]);

//        ProductVariant::create([
//            "description" => "30 Tonnes",
//            "unit" => "Tonne",
//            "quantity" => 30,
//            "cost" => 640000,
//            "product_id"=>$product->id
//        ]);

        $product = Product::create([
            "name" => "Pebble Stone",
        ]);

        ProductVariant::create([
            "description" => "20 Tonnes",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
            "product_id"=>$product->id
        ]);

        ProductVariant::create([
            "description" => "25 Tonnes",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 575000,
            "product_id"=>$product->id
        ]);

//        ProductVariant::create([
//            "description" => "30 Tonnes",
//            "unit" => "Tonne",
//            "quantity" => 30,
//            "cost" => 640000,
//            "product_id"=>$product->id
//        ]);

        $product = Product::create([
            "name" => "Quarry Dust",
        ]);

        ProductVariant::create([
            "description" => "20 Tonnes",
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 470000,
            "product_id"=>$product->id
        ]);

        ProductVariant::create([
            "description" => "25 Tonnes",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
            "product_id"=>$product->id
        ]);

//        ProductVariant::create([
//            "description" => "30 Tonnes",
//            "unit" => "Tonne",
//            "quantity" => 30,
//            "cost" => 580000,
//            "product_id"=>$product->id
//        ]);

        $product = Product::create([
            "name" => "Cement Block",
        ]);

        ProductVariant::create([
            "description" => "Medium",
            "unit" => "150*200*400(mm)",
            "quantity" => 1,
            "cost" => 1300,
            "product_id"=>$product->id
        ]);

        ProductVariant::create([
            "description" => "Big",
            "unit" => "200*200*400(mm)",
            "quantity" => 1,
            "cost" => 1700,
            "product_id"=>$product->id
        ]);

        $product = Product::create([
            "name" => "River Sand",
        ]);

        ProductVariant::create([
            "description" => "Normal",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 350000,
            "product_id"=>$product->id
        ]);

        ProductVariant::create([
            "description" => "Salima",
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 750000,
            "product_id"=>$product->id
        ]);

    }
}
