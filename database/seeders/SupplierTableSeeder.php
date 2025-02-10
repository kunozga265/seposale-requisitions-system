<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            "name" => "Chawezi Resources",
            "phone_number" => "265123456789",
            "phone_number_other" => "265123456789",
            "address" => "Nathenje",
            "email" => "cr@chawezi.com",
        ]);

        Supplier::create([
            "name" => "Supplier 1",
            "phone_number" => "265123456789",
              "phone_number_other" => "265123456789",
            "address" => "Nathenje",
            "email" => "cr@chawezi.com",
        ]);

        Supplier::create([
            "name" => "Supplier 2",
            "phone_number" => "265123456789",
              "phone_number_other" => "265123456789",
            "address" => "Nathenje",
            "email" => "cr@chawezi.com",
        ]);

    }
}
