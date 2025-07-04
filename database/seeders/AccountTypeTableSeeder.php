<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountType::create([
            'name' => 'Current Assets',
            'slug' => 'current-assets',
            'statement' => 'balance-sheet',
        ]);
        AccountType::create([
            'name' => 'Non Current Assets',
            'slug' => 'non-current-assets',
            'statement' => 'balance-sheet',
        ]);
        AccountType::create([
            'name' => 'Current Liabilities',
            'slug' => 'current-liabilities',
            'statement' => 'balance-sheet',
        ]);
        AccountType::create([
            'name' => 'Long-term Liabilities',
            'slug' => 'long-term-liabilities',
            'statement' => 'balance-sheet',
        ]);
        AccountType::create([
            'name' => 'Equity',
            'slug' => 'equity',
            'statement' => 'balance-sheet',
        ]);
        AccountType::create([
            'name' => 'Income',
            'slug' => 'income',
            'statement' => 'income-statement',
        ]);
        AccountType::create([
            'name' => 'Expenses',
            'slug' => 'expenses',
            'statement' => 'income-statement',
        ]);
        AccountType::create([
            'name' => 'Other Income',
            'slug' => 'other-income',
            'statement' => 'income-statement',
        ]);
        AccountType::create([
            'name' => 'Other Expenses',
            'slug' => 'other-expenses',
            'statement' => 'income-statement',
        ]);
      
    }
}
