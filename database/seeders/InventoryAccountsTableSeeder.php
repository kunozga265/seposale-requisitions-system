<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InventoryAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventory = \App\Models\Inventory::find(1); // Thanthwe Cement
        $inventory->update([
            'inventory_account_id' => \App\Models\AccountingAccount::where('code', 1101)->first()->id,
            'revenue_account_id' => \App\Models\AccountingAccount::where('code', 4110)->first()->id,
            'cogs_account_id' => \App\Models\AccountingAccount::where('code', 5110)->first()->id,
        ]);
        $inventory = \App\Models\Inventory::find(2); // Akshar Cement
        $inventory->update([
            'inventory_account_id' => \App\Models\AccountingAccount::where('code', 1101)->first()->id,
            'revenue_account_id' => \App\Models\AccountingAccount::where('code', 4110)->first()->id,
            'cogs_account_id' => \App\Models\AccountingAccount::where('code', 5110)->first()->id,
        ]);
        $inventory = \App\Models\Inventory::find(3); // River Sand       
        $inventory->update([
            'inventory_account_id' => \App\Models\AccountingAccount::where('code', 1102)->first()->id,
            'revenue_account_id' => \App\Models\AccountingAccount::where('code', 4120)->first()->id,
            'cogs_account_id' => \App\Models\AccountingAccount::where('code', 5120)->first()->id,
        ]);
        $inventory = \App\Models\Inventory::find(4); // Quarry Stones
        $inventory->update([
            'inventory_account_id' => \App\Models\AccountingAccount::where('code', 1103)->first()->id,
            'revenue_account_id' => \App\Models\AccountingAccount::where('code', 4130)->first()->id,
            'cogs_account_id' => \App\Models\AccountingAccount::where('code', 5130)->first()->id,
        ]);
    
    }
}
