<?php

namespace Database\Seeders;

use App\Models\AccountingAccount;
use Illuminate\Database\Seeder;

class AccountingAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Current Assets accounts
        AccountingAccount::create([
            'name' => 'Cash on hand',
            'code' => 1010,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cash on hand',
            'code' => 1011,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Operating Budget',
            'code' => 1012,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Bank Accounts: NB Seposale',
            'code' => 1020,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Bank Account: Standard Bank',
            'code' => 1021,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Bank Account: NB Njewa One Stop Shop',
            'code' => 1022,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Bank Account: NB Airwing One Stop Shop',
            'code' => 1023,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Bank Account: Centenary Bank',
            'code' => 1024,
            'type' => "DEBIT",
            'special_type' => "WALLET",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Accounts Receivable',
            'code' => 1030,
            'type' => "DEBIT",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Allowance for doubtful debts account',
            'code' => 1031,
            'type' => "CREDIT",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);
        AccountingAccount::create([
            'name' => 'Short-term Investments',
            'code' => 1032,
            'type' => "DEBIT",
            'accounts_group_id' => 1, // Assuming 1 is the ID for Current Assets
        ]);

        // Create Inventory accounts
        AccountingAccount::create([
            'name' => 'Cement Inventory',
            'code' => 1041,
            'type' => "DEBIT",
            "special_type" => "INVENTORY",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'River Sand Inventory',
            'code' => 1042,
            'type' => "DEBIT",
            "special_type" => "INVENTORY",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Quarry Stone Inventory',
            'code' => 1043,
            'type' => "DEBIT",
            "special_type" => "INVENTORY",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Quarry Dust Inventory',
            'code' => 1044,
            'type' => "DEBIT",
            "special_type" => "INVENTORY",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Pebble Stone Inventory',
            'code' => 1045,
            'type' => "DEBIT",
            "special_type" => "INVENTORY",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Cement Blocks Inventory',
            'code' => 1046,
            'type' => "DEBIT",
            "special_type" => "INVENTORY",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Other Inventory',
            'code' => 1049,
            'type' => "DEBIT",
            "special_type" => "INVENTORY",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Prepaid Expenses',
            'code' => 1050,
            'type' => "DEBIT",
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);

        // Create Njewa accounts
        AccountingAccount::create([
            'name' => 'Njewa Cement Inventory',
            'code' => 1101,
            'type' => "DEBIT",
            'special_type' => 'INVENTORY-OSS',
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Njewa River Sand Inventory',
            'code' => 1102,
            'type' => "DEBIT",
            'special_type' => 'INVENTORY-OSS',
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Quarry Inventory',
            'code' => 1103,
            'type' => "DEBIT",
            'special_type' => 'INVENTORY-OSS',
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cement Blocks Inventory',
            'code' => 1104,
            'type' => "DEBIT",
            'special_type' => 'INVENTORY-OSS',
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Other Inventory',
            'code' => 1109,
            'type' => "DEBIT",
            'special_type' => 'INVENTORY-OSS',
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Raw Materials',
            'code' => 1110,
            'type' => "DEBIT",
            'special_type' => 'INVENTORY-OSS',
            'accounts_group_id' => 2, // Assuming 2 is the ID for Inventory Assets
        ]);

        // Create Property Plant and Equipment (PPE) accounts
        AccountingAccount::create([
            'name' => 'Land',
            'code' => 1510,
            'type' => "DEBIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Buildings',
            'code' => 1520,
            'type' => "DEBIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Vehicles',
            'code' => 1530,
            'type' => "DEBIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Office Equipment',
            'code' => 1540,
            'type' => "DEBIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Warehouse Equipment',
            'code' => 1550,
            'type' => "DEBIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Accumulated Depreciation - Buildings',
            'code' => 1580,
            'type' => "CREDIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Accumulated Depreciation - Vehicles',
            'code' => 1581,
            'type' => "CREDIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Accumulated Depreciation - Office Equipment',
            'code' => 1582,
            'type' => "CREDIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);
        AccountingAccount::create([
            'name' => 'Accumulated Depreciation - Warehouse Equipment',
            'code' => 1583,
            'type' => "CREDIT",
            'accounts_group_id' => 3, // Assuming 3 is the ID for Property Plant and Equipment (PPE)
        ]);

        // Create Intangible Assets accounts
        AccountingAccount::create([
            'name' => 'Software Licenses',
            'code' => 1810,
            'type' => "DEBIT",
            'accounts_group_id' => 4, // Assuming 4 is the ID for Intangible Assets
        ]);
        AccountingAccount::create([
            'name' => 'Amortization of Software Licenses',
            'code' => 1820,
            'type' => "CREDIT",
            'accounts_group_id' => 4, // Assuming 4 is the ID for Intangible Assets
        ]);

        // Create Current Liabilities accounts
        AccountingAccount::create([
            'name' => 'Accounts Payable',
            'code' => 2010,
            'type' => "CREDIT",
            'accounts_group_id' => 5, // Assuming 5 is the ID for Current Liabilities
        ]);
        AccountingAccount::create([
            'name' => 'Short-term Loans Payable',
            'code' => 2020,
            'type' => "CREDIT",
            'accounts_group_id' => 5, // Assuming 5 is the ID for Current Liabilities
        ]);
        AccountingAccount::create([
            'name' => 'Salaries and Wages Payable',
            'code' => 2030,
            'type' => "CREDIT",
            'accounts_group_id' => 5, // Assuming 5 is the ID for Current Liabilities
        ]);
        AccountingAccount::create([
            'name' => 'Taxes Payable',
            'code' => 2040,
            'type' => "CREDIT",
            'accounts_group_id' => 5, // Assuming 5 is the ID for Current Liabilities
        ]);
        AccountingAccount::create([
            'name' => 'Unearned Revenue',
            'code' => 2050,
            'type' => "CREDIT",
            'accounts_group_id' => 5, // Assuming 5 is the ID for Current Liabilities
        ]);
        AccountingAccount::create([
            'name' => 'Accrued Expenses',
            'code' => 2060,
            'type' => "CREDIT",
            'accounts_group_id' => 5, // Assuming 5 is the ID for Current Liabilities
        ]);


        // Create Long-term Liabilities accounts
        AccountingAccount::create([
            'name' => 'Long-term Loans Payable',
            'code' => 2510,
            'type' => "CREDIT",
            'accounts_group_id' => 6, // Assuming 6 is the ID for Long-term Liabilities
        ]);

        // Create Equity accounts
        AccountingAccount::create([
            'name' => 'Capital/Owners Contribution',
            'code' => 3010,
            'type' => "CREDIT",
            'accounts_group_id' => 7, // Assuming 7 is the ID for Equity
        ]);
        AccountingAccount::create([
            'name' => 'Retained Earnings',
            'code' => 3020,
            'type' => "CREDIT",
            'accounts_group_id' => 7, // Assuming 7 is the ID for Equity
        ]);

        // Create Income accounts
        AccountingAccount::create([
            'name' => 'Cement Sales Revenue',
            'code' => 4010,
            'type' => "CREDIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'River Sand Sales Revenue',
            'code' => 4020,
            'type' => "CREDIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Quarry Stone Sales Revenue',
            'code' => 4030,
            'type' => "CREDIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Quarry Dust Sales Revenue',
            'code' => 4040,
            'type' => "CREDIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Pebble Stone Sales Revenue',
            'code' => 4050,
            'type' => "CREDIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Cement Blocks Sales Revenue',
            'code' => 4060,
            'type' => "CREDIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Other Sales Revenue',
            'code' => 4070,
            'type' => "CREDIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Sales Returns and Allowances',
            'code' => 4080,
            'type' => "DEBIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Sales Discounts',
            'code' => 4090,
            'type' => "DEBIT",
            'special_type' => "REVENUE",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        // Create Njewa Income accounts
        AccountingAccount::create([
            'name' => 'Njewa Cement Sales Revenue',
            'code' => 4110,
            'type' => "CREDIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa River Sand Sales Revenue',
            'code' => 4120,
            'type' => "CREDIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Quarry Stone Sales Revenue',
            'code' => 4130,
            'type' => "CREDIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Quarry Dust Sales Revenue',
            'code' => 4140,
            'type' => "CREDIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Pebble Stone Sales Revenue',
            'code' => 4150,
            'type' => "CREDIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cement Blocks Sales Revenue',
            'code' => 4160,
            'type' => "CREDIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Other Sales Revenue',
            'code' => 4170,
            'type' => "CREDIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Sales Returns and Allowances',
            'code' => 4180,
            'type' => "DEBIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Sales Discounts',
            'code' => 4190,
            'type' => "DEBIT",
            'special_type' => "REVENUE-OSS",
            'accounts_group_id' => 8, // Assuming 8 is the ID for Income
        ]);

        // Create Cost of Goods Sold (COGS) accounts
        AccountingAccount::create([
            'name' => 'Cost of Goods Sold - Cement',
            'code' => 5010,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Cost of Goods Sold - River Sand',
            'code' => 5020,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Cost of Goods Sold - Quarry Stone',
            'code' => 5030,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Cost of Goods Sold - Quarry Dust',
            'code' => 5040,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Cost of Goods Sold - Pebble Stone',
            'code' => 5050,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Cost of Goods Sold - Cement Blocks',
            'code' => 5060,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Cost of Goods Sold - Other',
            'code' => 5070,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Direct Labor Costs',
            'code' => 5080,
            'type' => "DEBIT",
            'special_type' => 'COGS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);

        // Create Njewa Cost of Goods Sold (COGS) accounts
        AccountingAccount::create([
            'name' => 'Njewa Cost of Goods Sold - Cement',
            'code' => 5110,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cost of Goods Sold - River Sand',
            'code' => 5120,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cost of Goods Sold - Quarry Stone',
            'code' => 5130,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cost of Goods Sold - Quarry Dust',
            'code' => 5140,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cost of Goods Sold - Pebble Stone',
            'code' => 5150,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cost of Goods Sold - Cement Blocks',
            'code' => 5160,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Cost of Goods Sold - Other',
            'code' => 5170,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
            'name' => 'Njewa Direct Labor Costs',
            'code' => 5180,
            'type' => "DEBIT",
            'special_type' => 'COGS-DIRECT-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);
        AccountingAccount::create([
           'name' => 'Njewa Cost of Goods Sold - Raw Materials',
            'code' => 5190,
            'type' => "DEBIT",
            'special_type' => 'COGS-OSS',
            'accounts_group_id' => 9, // Assuming 9 is the ID for Cost of Goods Sold (COGS)
        ]);

        // Create Selling and Marketing Expenses accounts
        AccountingAccount::create([
            'name' => 'Advertising and Promotion Expenses',
            'code' => 6010,
            'type' => "DEBIT",
            'accounts_group_id' => 10, // Assuming 10 is the ID for Selling and Marketing Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Sales Salaries and Commissions',
            'code' => 6020,
            'type' => "DEBIT",
            'accounts_group_id' => 10, // Assuming 10 is the ID for Selling and Marketing Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Delivery and Shipping Expenses',
            'code' => 6030,
            'type' => "DEBIT",
            'accounts_group_id' => 10, // Assuming 10 is the ID for Selling and Marketing Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Marketing Research Expenses',
            'code' => 6040,
            'type' => "DEBIT",
            'accounts_group_id' => 10, // Assuming 10 is the ID for Selling and Marketing Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Operating Expenses',
            'code' => 6050,
            'type' => "DEBIT",
            'accounts_group_id' => 10, // Assuming 10 is the ID for Selling and Marketing Expenses
        ]);

        // Create General and Administrative Expenses accounts
        AccountingAccount::create([
            'name' => 'Salaries and Wages - Admin',
            'code' => 7010,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Office Rent Expenses',
            'code' => 7020,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Utilities Expenses',
            'code' => 7030,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Insurance Expenses',
            'code' => 7040,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Office Supplies Expenses',
            'code' => 7050,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Depreciation Expense - Buildings',
            'code' => 7060,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Depreciation Expense - Vehicles',
            'code' => 7061,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Depreciation Expense - Office Equipment',
            'code' => 7062,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Depreciation Expense - Warehouse Equipment',
            'code' => 7063,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Amortization Expense - Software Licenses',
            'code' => 7070,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Legal and Accounting (Professional) Fees',
            'code' => 7080,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Bank Charges and Fees',
            'code' => 7090,
            'type' => "DEBIT",
            'special_type' => "BANK-CHARGES",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Repairs and Maintenance Expenses',
            'code' => 7100,
            'type' => "DEBIT",
            'accounts_group_id' => 11, // Assuming 11 is the ID for General and Administrative Expenses
        ]);


        // Create Other Income accounts
        AccountingAccount::create([
            'name' => 'Interest Income',
            'code' => 8010,
            'type' => "CREDIT",
            'accounts_group_id' => 12, // Assuming 12 is the ID for Other Income
        ]);
        AccountingAccount::create([
            'name' => 'Gain on Sale of Assets',
            'code' => 8020,
            'type' => "CREDIT",
            'accounts_group_id' => 12, // Assuming 12 is the ID for Other Income
        ]);
        // Create Other Expenses accounts
        AccountingAccount::create([
            'name' => 'Interest Expense',
            'code' => 9010,
            'type' => "DEBIT",
            'accounts_group_id' => 13, // Assuming 13 is the ID for Other Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Loss on Sale of Assets',
            'code' => 9020,
            'type' => "DEBIT",
            'accounts_group_id' => 13, // Assuming 13 is the ID for Other Expenses
        ]);
        AccountingAccount::create([
            'name' => 'Miscellaneous Expenses',
            'code' => 9040,
            'type' => "DEBIT",
            'accounts_group_id' => 13, // Assuming 13 is the ID for Other Expenses
        ]);
    }
}
