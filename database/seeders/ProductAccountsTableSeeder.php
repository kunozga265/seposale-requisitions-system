<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountingAccount;
use Illuminate\Database\Seeder;

class ProductAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = \App\Models\Product::find(1); //cement
        $product->update([
            'inventory_account_id' => AccountingAccount::where('code', 1041)->first()->id,
            'revenue_account_id' => AccountingAccount::where('code', 4010)->first()->id,
            'cogs_account_id' => AccountingAccount::where('code', 5010)->first()->id,
        ]);

        $product = \App\Models\Product::find(6); //river sand
          $product->update([
            'inventory_account_id' => AccountingAccount::where('code', 1042)->first()->id,
            'revenue_account_id' => AccountingAccount::where('code', 4020)->first()->id,
            'cogs_account_id' => AccountingAccount::where('code', 5020)->first()->id,
        ]);

        $product = \App\Models\Product::find(2); //quarry stones
        $product->update([
            'inventory_account_id' => AccountingAccount::where('code', 1043)->first()->id,
            'revenue_account_id' => AccountingAccount::where('code', 4030)->first()->id,
            'cogs_account_id' => AccountingAccount::where('code', 5030)->first()->id,
        ]);

        $product = \App\Models\Product::find(4); //quarry dust
        $product->update([
            'inventory_account_id' => AccountingAccount::where('code', 1044)->first()->id,
            'revenue_account_id' => AccountingAccount::where('code', 4040)->first()->id,
            'cogs_account_id' => AccountingAccount::where('code', 5040)->first()->id,
        ]);

        $product = \App\Models\Product::find(3); //pebble stones
        $product->update([
            'inventory_account_id' => AccountingAccount::where('code', 1045)->first()->id,
            'revenue_account_id' => AccountingAccount::where('code', 4050)->first()->id,
            'cogs_account_id' => AccountingAccount::where('code', 5050)->first()->id,
        ]);
     
        $product = \App\Models\Product::find(5); //cement blocks
        $product->update([
            'inventory_account_id' => AccountingAccount::where('code', 1046)->first()->id,
            'revenue_account_id' => AccountingAccount::where('code', 4060)->first()->id,
            'cogs_account_id' => AccountingAccount::where('code', 5060)->first()->id,
        ]);
        
      
        $product = \App\Models\Product::find(7); //other
        $product->update([
            'inventory_account_id' => AccountingAccount::where('code', 1049)->first()->id,
            'revenue_account_id' => AccountingAccount::where('code', 4070)->first()->id,
            'cogs_account_id' => AccountingAccount::where('code', 5070)->first()->id,
        ]);

        // $product = \App\Models\Product::find(8); //services
        // $product->update([
        //     'inventory_account_id' => AccountingAccount::where('code', 1041)->first()->id,
        //     'revenue_account_id' => AccountingAccount::where('code', 4010)->first()->id,
        //     'cogs_account_id' => AccountingAccount::where('code', 5010)->first()->id,
        // ]);
    }
}
