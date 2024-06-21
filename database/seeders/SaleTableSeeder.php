<?php

namespace Database\Seeders;

use App\Http\Controllers\AppController;
use App\Http\Controllers\SaleController;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleTableSeeder extends Seeder
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
           $sale->update([
               "code_alt" => (new SaleController())->getSaleCodeNumber()
           ]);
        }
    }
}
