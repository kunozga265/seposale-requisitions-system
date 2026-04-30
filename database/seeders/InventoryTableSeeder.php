<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inventory::create([
            "name"=>"Thanthwe (42.5) Cement",
            "units"=>"Bag",
            "cost"=>26000,
            "site_id"=>1,
            "available_stock"=>200,
            "uncollected_stock"=>50,
            "threshold"=>100,
        ]);

        Inventory::create([
            "name"=>"Akshar (32.5) Cement",
            "units"=>"Bag",
            "cost"=>22500,
            "site_id"=>1,
            "available_stock"=>60,
            "uncollected_stock"=>10,
            "threshold"=>100,
        ]);

        Inventory::create([
            "name"=>"River Sand",
            "units"=>"Tonne",
            "cost"=>24000,
            "site_id"=>1,
            "available_stock"=>50,
            "uncollected_stock"=>0,
            "threshold"=>50,
        ]);

        Inventory::create([
            "name"=>"Quarry Stone",
            "units"=>"Tonne",
            "cost"=>28000,
            "site_id"=>1,
            "available_stock"=>30,
            "uncollected_stock"=>30,
            "threshold"=>30,
        ]);
    }
}
