<?php

namespace Database\Seeders;


use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Motorbike', 'capacity' => 'Regular'],
            ['name' => 'Truck', 'capacity' => '2 Ton'],
            ['name' => 'Truck', 'capacity' => '5 Ton'],
            ['name' => 'Truck', 'capacity' => '10 Ton'],
            ['name' => 'Truck', 'capacity' => '15 Ton'],
        ];

        foreach ($types as $type) {
            VehicleType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}