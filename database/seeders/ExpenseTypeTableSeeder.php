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
        ExpenseType::create([
            "name" => "Salary"
        ]);

        ExpenseType::create([
            "name" => "Operations"
        ]);

        ExpenseType::create([
            "name" => "Stationary"
        ]);

        ExpenseType::create([
            "name" => "Transportation"
        ]);

        ExpenseType::create([
            "name" => "Other"
        ]);

        ExpenseType::create([
            "name" => "Airtime"
        ]);

        ExpenseType::create([
            "name" => "Bills"
        ]);

    }
}
