<?php

namespace Database\Seeders;

// database/seeders/TransportOptionSeeder.php

use App\Models\TransportOption;
use App\Models\VehicleType;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TransportOptionTableSeeder extends Seeder
{
  public function run(): void
  {
    // $vehicleTypes = VehicleType::pluck('id', 'name');
    // $zones = Zone::pluck('id', 'name');

    $locations = [
      [
        "name" => "6 Miles",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Area 25 - A",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],

      [
        "name" => "Airwing - Acacia",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Airwing - After Barons",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 200000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 200000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 200000
          ],
          [
            "id" => 6, //river sand
            "amount" => 100000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Airwing - Four Ways",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Area 1 (Falls)",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 10",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 12",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 13 (City Center)",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 14",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 15",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 17",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 22",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 23",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 24",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 25 - Magwero",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2700,
        "2_ton" => 108000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 27",
        "cost" => 0,
        "level" => 1,
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 3",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 32",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 34",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 36",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 38",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 39",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 43",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 44",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2750,
        "2_ton" => 110000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 45",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000

      ],
      [
        "name" => "Area 46",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 47 - Sector 1",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 49 - Old Gulliver",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Area 50",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Area 51",
        "cost" => 0,
        "level" => 1,
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Area 58",
        "cost" => 0,
        "level" => 1,
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Area 9",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],

      [
        "name" => "Airwing - Baron",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 200000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 200000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 200000
          ],
          [
            "id" => 6, //river sand
            "amount" => 100000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Airwing - Before Four Ways",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Area 49 - Bhagdad",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Biwi",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Airwing - Buli",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Airwing - Buli Road",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Area 25 - B",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Bunda Road - College",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 200000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 200000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 200000
          ],
          [
            "id" => 6, //river sand
            "amount" => 100000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 7500,
        "2_ton" => 300000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Bunda Road - Chiseka",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 3500,
        "2_ton" => 140000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Likuni - Bwemba",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 110000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Bypass",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 25 - C",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "CCDC",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Likuni - Chigwiri",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2750,
        "2_ton" => 110000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Chilinde",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Likuni - Chinsapo 2",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "motorbike" => 2000,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Chitedze",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Chitipi",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Likuni - Chitipi Road",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Area 25 - Chitukula",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2700,
        "2_ton" => 108000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 25 - Choto",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2700,
        "2_ton" => 108000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 25 - Dzenza",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Likuni - Forest",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,

      ],

      [
        "name" => "Bunda Road - Guzani",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 3200,
        "2_ton" => 128000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Kaliyeka",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Area 25 -Kanengo",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2700,
        "2_ton" => 108000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Kaphiri",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Kawale 1",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Kawale 2",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Likuni - Chinsapo 1",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],

      [
        "name" => "Likuni - Magulosale",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Likuni - Malewezi",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Airwing - Mbavi",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 200000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 200000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 200000
          ],
          [
            "id" => 6, //river sand
            "amount" => 100000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Mbwatalika",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Mchesi",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000
      ],
      [
        "name" => "Bunda Road - Mitundu",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 200000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 200000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 200000
          ],
          [
            "id" => 6, //river sand
            "amount" => 100000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 3750,
        "2_ton" => 150000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Likuni - Mpapha",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ],
      [
        "name" => "Mpingu",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Area 25 - Msungwi",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 0
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2250,
        "2_ton" => 90000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Mtandile",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Mtandile - Chilimampunga",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Mtaya",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2750,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Airwing - Muzu",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "NRC",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Nanjiri",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2750,
        "2_ton" => 110000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "Nathenje",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 4000,
        "2_ton" => 160000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],
      [
        "name" => "New Airwing",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Area 49 - New Gulliver",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Area 49 - New Shire",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Njewa",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 0
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 0
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 0
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],

      [
        "name" => "Area 49 - Old Shire",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 80000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000,
      ],
      [
        "name" => "Old Town",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2500,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Bunda Road - Pondamali",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 3500,
        "2_ton" => 140000,
        "5_ton" => null,
        "10_ton" => 400000,
        "15_ton" => 500000,
        "20_ton" => 600000
      ],

      [
        "name" => "Airwing - Sankhani",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Area 47 - Sector 2",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 47 - Sector 3",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 47 - Sector 4",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Area 47 - Sector 5",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 1750,
        "2_ton" => 70000,
        "5_ton" => null,
        "10_ton" => 250000,
        "15_ton" => 300000,
        "20_ton" => 400000
      ],
      [
        "name" => "Likuni - Works",
        "cost" => 0,
        "costs" => [
          [
            "id" => 2, //quarry stone
            "amount" => 100000
          ],
          [
            "id" => 3, //pebble stone
            "amount" => 100000
          ],
          [
            "id" => 4, //quarry dust
            "amount" => 100000
          ],
          [
            "id" => 6, //river sand
            "amount" => 50000
          ],
        ],
        "level" => 1,
        "coordinates" => [],
        "motorbike" => 2000,
        "2_ton" => 100000,
        "5_ton" => null,
        "10_ton" => 350000,
        "15_ton" => 400000,
        "20_ton" => 500000,
      ]
    ];



    $motorbike = VehicleType::updateOrCreate(
      ['name' => 'Motorbike', 'capacity' => 'Regular'],
    );

    $ton_2 = VehicleType::updateOrCreate(
      ['name' => 'Truck', 'capacity' => '2 Ton'],
    );
    $ton_5 = VehicleType::updateOrCreate(
      ['name' => 'Truck', 'capacity' => '5 Ton'],
    );
    $ton_10 = VehicleType::updateOrCreate(
      ['name' => 'Truck', 'capacity' => '10 Ton'],
    );
    $ton_15 = VehicleType::updateOrCreate(
      ['name' => 'Truck', 'capacity' => '15 Ton'],
    );
    $ton_20 = VehicleType::updateOrCreate(
      ['name' => 'Truck', 'capacity' => '20 Ton'],
    );




    foreach ($locations as $location) {
      //create zone

      Log::info($location['name']);

      $zone = \App\Models\Zone::updateOrCreate(
        [
          'name' => $location['name']
        ],
        [
          'cost' => $location['cost'],
          'costs' => json_encode(array_key_exists('costs', $location) ? $location['costs'] : []),
          'level' => 1,
          'coordinates' => json_encode(array_key_exists('coordinates', $location) ? $location['coordinates'] : []),
        ]
      );

      //motorbike
      TransportOption::updateOrCreate(
        [
          'vehicle_type_id' => $motorbike->id,
          'zone_id' => $zone->id,
          'product_id' => 1, // Cement example
        ],
        [
          'cost' => $location['motorbike'],
          'max' => 3,
          'available' => true,
          'meta' => json_encode([
            "variants" => [],
          ],),
        ]
      );

      $data = [
        //2 tonner
        [
          'vehicle_type_id' => $ton_2->id,
          'cost' => $location['2_ton'],
          'products' => [
            [
              'id' => 1, //cement
              'max' => 40,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 2, //quarry stone
              'max' => 2,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 3, //pebble stone
              'max' => 2,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 4, //quarry dust
              'max' => 2,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 5, //blocks
              'max' => 170,
              'meta' => [
                "variants" => [
                  [
                    "id" => 9,
                    "max" => 200,
                  ],
                  [
                    "id" => 10,
                    "max" => 170,
                  ]
                ]
              ],
            ],
            [
              'id' => 6, //sand
              'max' => 2,
              'meta' => [
                "variants" => [],
              ],
            ],
          ]
        ],
        //10 tonner
        [
          'vehicle_type_id' => $ton_10->id,
          'cost' => $location['10_ton'],
          'products' => [
            [
              'id' => 1, //cement
              'max' => 250,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 2, //quarry stone
              'max' => 10,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 3, //pebble stone
              'max' => 10,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 4, //quarry dust
              'max' => 10,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 5, //cement blocks
              'max' => 600,
              'meta' => [
                "variants" => [
                  [
                    "id" => 9,
                    "max" => 700,
                  ],
                  [
                    "id" => 10,
                    "max" => 600,
                  ]
                ]
              ],
            ],
            [
              'id' => 6, //river sand
              'max' => 10,
              'meta' => [
                "variants" => [],
              ],
            ],
          ]
        ],
        //15 tonner
        [
          'vehicle_type_id' => $ton_15->id,
          'cost' => $location['15_ton'],
          'products' => [
            [
              'id' => 1, //cement
              'max' => 300,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 2, //quarry stone
              'max' => 15,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 3, //pebble stone
              'max' => 15,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 4, //quarry dust
              'max' => 15,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 5, //cement blocks
              'max' => 800,
              'meta' => [
                "variants" => [
                  [
                    "id" => 9,
                    "max" => 900,
                  ],
                  [
                    "id" => 10,
                    "max" => 800,
                  ]
                ]
              ],
            ],
            [
              'id' => 6, //river sand
              'max' => 15,
              'meta' => [
                "variants" => [],
              ],
            ],
          ]
        ],
        //20 tonner
        [
          'vehicle_type_id' => $ton_20->id,
          'cost' => $location['20_ton'],
          'products' => [
            [
              'id' => 1, //cement
              'max' => 400,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 2, //quarry stone
              'max' => 20,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 3, //pebble stone
              'max' => 20,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 4, //quarry dust
              'max' => 20,
              'meta' => [
                "variants" => [],
              ],
            ],
            [
              'id' => 5, //cement blocks
              'max' => 600,
              'meta' => [
                "variants" => [
                  [
                    "id" => 9,
                    "max" => 1000,
                  ],
                  [
                    "id" => 10,
                    "max" => 1200,
                  ]
                ]
              ],
            ],
            [
              'id' => 6, //river sand
              'max' => 20,
              'meta' => [
                "variants" => [],
              ],
            ],
          ]
        ],
      ];

      foreach ($data as $compound) {
        foreach ($compound['products'] as $product) {
          //2 tonner
          TransportOption::updateOrCreate(
            [
              'vehicle_type_id' => $compound['vehicle_type_id'],
              'zone_id' => $zone->id,
              'product_id' => $product['id'], // Cement example
            ],
            [
              'cost' => $compound['cost'],
              'max' => $product['max'],
              'available' => true,
              'meta' => json_encode($product['meta']),
            ]
          );
        }
      }
    }
  }
}
