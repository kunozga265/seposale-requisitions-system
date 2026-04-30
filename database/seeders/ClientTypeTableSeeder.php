<?php

namespace Database\Seeders;

use App\Models\ClientType;
use Illuminate\Database\Seeder;

class ClientTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientType::create([
            "name" => "Engineer"
        ]);
        ClientType::create([
            "name" => "Architect"
        ]);
        ClientType::create([
            "name" => "Quantity Surveyor"
        ]);
        ClientType::create([
            "name" => "Foreman"
        ]);
        ClientType::create([
            "name" => "Builder"
        ]);
        ClientType::create([
            "name" => "Representative"
        ]);
        ClientType::create([
            "name" => "Owner"
        ]);
        ClientType::create([
            "name" => "Contractor"
        ]);
        ClientType::create([
            "name" => "Clerk"
        ]);
      
    }
}
