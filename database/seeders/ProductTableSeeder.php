<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            "slug" => Str::slug("Cement"),

        ]);

        ProductVariant::create([
            "description" => "Thanthwe (42.5)",
            "slug" => Str::slug("Thanthwe (42.5)"),
            "unit" => "50kg",
            "quantity" => 1,
            "cost" => 20000,
            "cost_original" => 20000,
            "product_id" => $product->id
        ]);

        ProductVariant::create([
            "description" => "Akshar (32.5)",
            "slug" => Str::slug("Akshar (32.5)"),
            "unit" => "50 kg",
            "quantity" => 1,
            "cost" => 18000,
            "cost_original" => 18000,
            "product_id" => $product->id
        ]);

        $product = Product::create([
            "name" => "Quarry Stone",
            "slug" => Str::slug("Quarry Stone"),
        ]);

        ProductVariant::create([
            "description" => "20 Tonnes",
            "slug" => Str::slug("20 Tonnes"),
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
            "cost_original" => 510000,
            "product_id" => $product->id
        ]);

        ProductVariant::create([
            "description" => "25 Tonnes",
            "slug" => Str::slug("25 Tonnes"),
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 575000,
            "cost_original" => 575000,
            "product_id" => $product->id
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
            "slug" => Str::slug("Pebble Stone"),
        ]);

        ProductVariant::create([
            "description" => "20 Tonnes",
            "slug" => Str::slug("20 Tonnes"),
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 510000,
            "cost_original" => 510000,
            "product_id" => $product->id
        ]);

        ProductVariant::create([
            "description" => "25 Tonnes",
            "slug" => Str::slug("25 Tonnes"),
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 575000,
            "cost_original" => 575000,
            "product_id" => $product->id
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
            "slug" => Str::slug("Quarry Dust"),
        ]);

        ProductVariant::create([
            "description" => "20 Tonnes",
            "slug" => Str::slug("20 Tonnes"),
            "unit" => "Tonne",
            "quantity" => 20,
            "cost" => 470000,
            "cost_original" => 470000,
            "product_id" => $product->id
        ]);

        ProductVariant::create([
            "description" => "25 Tonnes",
            "slug" => Str::slug("25 Tonnes"),
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 525000,
            "cost_original" => 525000,
            "product_id" => $product->id
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
            "slug" => Str::slug("Cement Block"),
        ]);

        ProductVariant::create([
            "description" => "Medium",
            "slug" => Str::slug("Medium"),
            "unit" => "150*200*400(mm)",
            "quantity" => 1,
            "cost" => 1300,
            "cost_original" => 1300,
            "product_id" => $product->id
        ]);

        ProductVariant::create([
            "description" => "Big",
            "slug" => Str::slug("Big"),
            "unit" => "200*200*400(mm)",
            "quantity" => 1,
            "cost" => 1700,
            "cost_original" => 1700,
            "product_id" => $product->id
        ]);

        $product = Product::create([
            "name" => "River Sand",
            "slug" => Str::slug("River Sand"),
        ]);

        ProductVariant::create([
            "description" => "Normal",
            "slug" => Str::slug("Normal"),
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 350000,
            "cost_original" => 350000,
            "product_id" => $product->id
        ]);

        ProductVariant::create([
            "description" => "Salima",
            "slug" => Str::slug("Salima"),
            "unit" => "Tonne",
            "quantity" => 25,
            "cost" => 750000,
            "cost_original" => 750000,
            "product_id" => $product->id
        ]);

        $product = Product::create([
            "name" => "Other",
            "slug" => Str::slug("Other"),
        ]);
        $product = Product::create([
            "name" => "Services",
            "slug" => Str::slug("Services"),
        ]);
    }
}
