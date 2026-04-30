<?php

namespace Database\Seeders;

use App\Models\AccountsGroup;
use Illuminate\Database\Seeder;

class AccountsGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountsGroup::create([
            'name' => 'Current Assets',
            'code' => 1000,
            'account_type_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountsGroup::create([
            'name' => 'Inventory',
            'code' => 1040,
            'account_type_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountsGroup::create([
            'name' => 'Property Plant and Equipment (PPE)',
            'code' => 1500,
            'account_type_id' => 2, // Assuming 2 is the ID for Non Current Assets
        ]);
        AccountsGroup::create([
            'name' => 'Intangible Assets',
            'code' => 1800,
            'account_type_id' => 2, // Assuming 2 is the ID for Non Current Assets
        ]);
        AccountsGroup::create([
            'name' => 'Current Liabilities',
            'code' => 2000,
            'account_type_id' => 3, // Assuming 3 is the ID for Current Liabilities
        ]);
        AccountsGroup::create([
            'name' => 'Long-term Liabilities',
            'code' => 2500,
            'account_type_id' => 4, // Assuming 4 is the ID for Long-term Liabilities
        ]);
        AccountsGroup::create([
            'name' => 'Equity',
            'code' => 3000,
            'account_type_id' => 5, // Assuming 5 is the ID for Equity
        ]);
        AccountsGroup::create([
            'name' => 'Income',
            'code' => 4000,
            'account_type_id' => 6, // Assuming 6 is the ID for Income
        ]);
        AccountsGroup::create([
            'name' => 'Cost of Goods Sold (COGS)',
            'code' => 5000,
            'account_type_id' => 7, // Assuming 7 is the ID for Expenses
        ]);
        AccountsGroup::create([
            'name' => 'Selling and Marketing Expenses ',
            'code' => 6000,
            'account_type_id' => 7, // Assuming 7 is the ID for Expenses
        ]);
        AccountsGroup::create([
            'name' => 'General and Administrative Expenses',
            'code' => 7000,
            'account_type_id' => 7, // Assuming 7 is the ID for Expenses
        ]);
        AccountsGroup::create([
            'name' => 'Other Income',
            'code' => 8000,
            'account_type_id' => 8, // Assuming 8 is the ID for Other Income
        ]);
        AccountsGroup::create([
            'name' => 'Other Expenses',
            'code' => 9000,
            'account_type_id' => 9, // Assuming 9 is the ID for Other Expenses
        ]);
        

    }
}
