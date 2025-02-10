<?php

namespace Database\Seeders;

use App\Models\Transporter;
use Illuminate\Database\Seeder;

class TransporterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transporter::create([
            "name" => "Chimutu Sanja",
            "phone_number" => "265123456789",
            "phone_number_other" => "265123456789",
            "email" => "cr@chawezi.com",
            "location" => "Nathenje",
            "registration_number" => "MZ 10178",
            "type" => "25 Tonne Tipper",
            "make" => "Mahindra",
        ]);

        Transporter::create([
            "name" => "Kelvin Manja",
            "phone_number" => "265123456789",
            "phone_number_other" => "265123456789",
            "email" => "cr@chawezi.com",
            "location" => "Nathenje",
            "registration_number" => "MZ 10178",
            "type" => "25 Tonne Tipper",
            "make" => "Mahindra",
        ]);

        Transporter::create([
            "name" => "Grant Maleki",
            "phone_number" => "265123456789",
            "phone_number_other" => "265123456789",
            "email" => "cr@chawezi.com",
            "location" => "Nathenje",
            "registration_number" => "MZ 10178",
            "type" => "25 Tonne Tipper",
            "make" => "Mahindra",
        ]);


    }
}
