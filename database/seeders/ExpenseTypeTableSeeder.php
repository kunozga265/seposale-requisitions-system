<?php

namespace Database\Seeders;

use App\Models\ExpenseType;
use Illuminate\Database\Seeder;

class ExpenseTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $expenses = [
            "Advertising and Marketing",
            "Operations",
            "Bank Charges",
            "Charitable Contributions",
            "Computer and Internet",
            "Ofiice Supplies",
            "Equipment Rental",
            "Telephone and Internet",
            "Travel",
            "Dues and Subscription",
            "Office",
            "Other General Administrative",
            "Rent or Lease Payments",
            "Stationery and Printing",
            "Utilities",
            "Long Term Investments",
            "Furniture and Fixtures",
            "Office Items",
            "Direct Labour Cost",
        ];

        foreach ($expenses as $expense) {

            ExpenseType::create([
                "name" => $expense
            ]);
        }
    }
}
