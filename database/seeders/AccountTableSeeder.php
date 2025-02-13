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
            "photo" => "images/nb.png",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 0,
        ]);

        Account::create([
            "name" => "Standard Bank",
            "number" => "9100006110794",
            "photo" => "images/std.png",
            "branch" => "Gateway Mall",
            "type" => "Current",
            "balance" => 0,
        ]);

        Account::create([
            "name" => "Njewa One Stop Shop",
            "number" => "1011915902",
            "photo" => "images/nb.png",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 0,
        ]);
        Account::create([
            "name" => "Airwing One Stop Shop",
            "number" => "1011934408",
            "photo" => "images/nb.png",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 0,
        ]);

        Account::create([
            "name" => "Centenary Bank",
            "number" => "9033830865024",
            "photo" => "images/cb.png",
            "branch" => "Gateway Mall",
            "type" => "Savings",
            "balance" => 0,
        ]);

        Account::create([
            "name" => "Petty Cash",
            "number" => "",
            "photo" => "images/petty_cash.jpeg",
            "branch" => "",
            "type" => "",
            "balance" => 0,
        ]);
    }
}
