<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::create([
            "name" => "National Bank",
            "number" => "1008405545",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 2500000,
        ]);

        Account::create([
            "name" => "Standard Bank",
            "number" => "9100006110794",
            "branch" => "Gateway Mall",
            "type" => "Current",
            "balance" => 4500000,
        ]);

        Account::create([
            "name" => "Njewa One Stop Shop",
            "number" => "1011915902",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 500000,
        ]);
        Account::create([
            "name" => "Airwing One Stop Shop",
            "number" => "1011915902",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 5000,
        ]);

        Account::create([
            "name" => "Centenary Bank",
            "number" => "9033830865024",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 85000,
        ]);

        Account::create([
            "name" => "Petty Cash",
            "number" => "",
            "branch" => "",
            "type" => "",
            "balance" => 50000,
        ]);
    }
}
