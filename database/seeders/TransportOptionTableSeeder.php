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
        "5_ton" => 280000,
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
        "5_ton" => 300000,
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
        "5_ton" => 300000,
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
        "5_ton" => 300000,
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
        "5_ton" => 280000,
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
        "coordinates" => [
          [
            "lat" => -13.974357246239151,
            "lng" =>  33.75111848115922
          ],
          [
            "lat" => -13.973821063439818,
            "lng" =>  33.751172125339515
          ],
          [
            "lat" => -13.97212401062183,
            "lng" =>  33.751193583011634
          ],
          [
            "lat" => -13.970780935415537,
            "lng" =>  33.751204311847694
          ],
          [
            "lat" => -13.969781432504186,
            "lng" =>  33.751193583011634
          ],
          [
            "lat" => -13.968453960994214,
            "lng" =>  33.75105410814286
          ],
          [
            "lat" => -13.96721498068256,
            "lng" =>  33.750919997692115
          ],
          [
            "lat" => -13.965975993707143,
            "lng" =>  33.75074833631516
          ],
          [
            "lat" => -13.964716176589071,
            "lng" =>  33.75052839517594
          ],
          [
            "lat" => -13.9634563525825,
            "lng" =>  33.75029236078263
          ],
          [
            "lat" => -13.96209240312411,
            "lng" =>  33.75006705522538
          ],
          [
            "lat" => -13.96119698153296,
            "lng" =>  33.74987393617631
          ],
          [
            "lat" => -13.960509795161672,
            "lng" =>  33.74975055456162
          ],
          [
            "lat" => -13.960848182646016,
            "lng" =>  33.74854892492295
          ],
          [
            "lat" => -13.961124098227067,
            "lng" =>  33.747427761554725
          ],
          [
            "lat" => -13.9614000134778,
            "lng" =>  33.7460008263588
          ],
          [
            "lat" => -13.961681134336912,
            "lng" =>  33.74458461999894
          ],
          [
            "lat" => -13.961858136183404,
            "lng" =>  33.74310940504075
          ],
          [
            "lat" => -13.961983078581405,
            "lng" =>  33.742186725139625
          ],
          [
            "lat" => -13.961863342118004,
            "lng" =>  33.74173611402512
          ],
          [
            "lat" => -13.96335223458786,
            "lng" =>  33.74124258756638
          ],
          [
            "lat" => -13.965195116139803,
            "lng" =>  33.7407597899437
          ],
          [
            "lat" => -13.967011953861576,
            "lng" =>  33.7403628230095
          ],
          [
            "lat" => -13.970468591221696,
            "lng" =>  33.73986124992371
          ],
          [
            "lat" => -13.9731130920056,
            "lng" =>  33.739732503891
          ],
          [
            "lat" => -13.974758081108453,
            "lng" =>  33.73971104621888
          ],
          [
            "lat" => -13.975049596938714,
            "lng" =>  33.74016165733338
          ]
        ],
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
        "5_ton" => 300000,
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
        "5_ton" => 300000,
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
        "5_ton" => 300000,
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
        "5_ton" => 300000,
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
        "5_ton" => 280000,
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
        "5_ton" => 280000,
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
        "5_ton" => 280000,
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
        "5_ton" => 280000,
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
        "5_ton" => 280000,
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
        "5_ton" => 280000,
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
        "5_ton" => 300000,
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
        "5_ton" => 280000 ,
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
        "5_ton" => 300000,
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
        "5_ton" => 300000,
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
        "5_ton" => 300000,
        "10_ton" => 320000,
        "15_ton" => 370000,
        "20_ton" => 450000
      ],
      [
        "name" => "Area 47 - Sector 2",
        "cost" => 0,
        "level" => 1,
        "coordinates" => [
          [
            "lat" => -13.97434683493454,
            "lng" =>  33.75112116336823
          ],
          [
            "lat" => -13.9742062822763,
            "lng" =>  33.75398576259614
          ],
          [
            "lat" => -13.973925176702426,
            "lng" =>  33.75597059726716
          ],
          [
            "lat" => -13.973545163066218,
            "lng" =>  33.75648021697999
          ],
          [
            "lat" => -13.972998567010224,
            "lng" =>  33.7569147348404
          ],
          [
            "lat" => -13.97155658833135,
            "lng" =>  33.75721514225007
          ],
          [
            "lat" => -13.97016665809833,
            "lng" =>  33.75793933868409
          ],
          [
            "lat" => -13.96879233681152,
            "lng" =>  33.75870645046235
          ],
          [
            "lat" => -13.96747527121694,
            "lng" =>  33.75944674015046
          ],
          [
            "lat" => -13.966272726488752,
            "lng" =>  33.76022458076478
          ],
          [
            "lat" => -13.96532005672844,
            "lng" =>  33.761029243469245
          ],
          [
            "lat" => -13.964185177227751,
            "lng" =>  33.762005567550666
          ],
          [
            "lat" => -13.963071115767308,
            "lng" =>  33.763008713722236
          ],
          [
            "lat" => -13.962342287598382,
            "lng" =>  33.76234352588654
          ],
          [
            "lat" => -13.961696752152378,
            "lng" =>  33.76172661781312
          ],
          [
            "lat" => -13.96107724466112,
            "lng" =>  33.761297464370735
          ],
          [
            "lat" => -13.960306762432593,
            "lng" =>  33.76088440418244
          ],
          [
            "lat" => -13.959041708322513,
            "lng" =>  33.76024067401887
          ],
          [
            "lat" => -13.957896385855925,
            "lng" =>  33.75962913036347
          ],
          [
            "lat" => -13.956823942387503,
            "lng" =>  33.759060502052314
          ],
          [
            "lat" => -13.95760484831944,
            "lng" =>  33.75749945640565
          ],
          [
            "lat" => -13.958323279440526,
            "lng" =>  33.75599741935731
          ],
          [
            "lat" => -13.959031296325724,
            "lng" =>  33.75447928905488
          ],
          [
            "lat" => -13.959536277628386,
            "lng" =>  33.75323474407197
          ],
          [
            "lat" => -13.959947550243,
            "lng" =>  33.751813173294074
          ],
          [
            "lat" => -13.960374440028824,
            "lng" =>  33.75037550926209
          ],
          [
            "lat" => -13.96052020709167,
            "lng" =>  33.74974250793458
          ],
          [
            "lat" => -13.96168634027552,
            "lng" =>  33.74998927116395
          ],
          [
            "lat" => -13.962862879382827,
            "lng" =>  33.75019848346711
          ],
          [
            "lat" => -13.96403420659768,
            "lng" =>  33.75040233135224
          ],
          [
            "lat" => -13.96495044062415,
            "lng" =>  33.75059008598328
          ],
          [
            "lat" => -13.96589270022606,
            "lng" =>  33.75071346759797
          ],
          [
            "lat" => -13.966975513131183,
            "lng" =>  33.75087976455689
          ],
          [
            "lat" => -13.968068732535562,
            "lng" =>  33.75100851058961
          ],
          [
            "lat" => -13.969151535212124,
            "lng" =>  33.75112652778626
          ],
          [
            "lat" => -13.970109394875108,
            "lng" =>  33.75119626522065
          ],
          [
            "lat" => -13.97116095361346,
            "lng" =>  33.75120162963868
          ],
          [
            "lat" => -13.972753899783822,
            "lng" =>  33.75117480754853
          ],
          [
            "lat" => -13.973675304793062,
            "lng" =>  33.75115871429444
          ]
        ],
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
        "coordinates" => [
          [
            "lat" => -13.963076321674505,
            "lng" =>  33.76298189163209
          ],
          [
            "lat" => -13.961316718342584,
            "lng" =>  33.76468777656556
          ],
          [
            "lat" => -13.95926556613978,
            "lng" =>  33.76661896705628
          ],
          [
            "lat" => -13.957766235215548,
            "lng" =>  33.76823902130128
          ],
          [
            "lat" => -13.956495961107295,
            "lng" =>  33.769537210464485
          ],
          [
            "lat" => -13.955371450318609,
            "lng" =>  33.76887202262879
          ],
          [
            "lat" => -13.953976216345827,
            "lng" =>  33.76772403717042
          ],
          [
            "lat" => -13.952331078875558,
            "lng" =>  33.76623272895814
          ],
          [
            "lat" => -13.951539742747544,
            "lng" =>  33.76540660858155
          ],
          [
            "lat" => -13.950373558241438,
            "lng" =>  33.76382946968079
          ],
          [
            "lat" => -13.949228192719325,
            "lng" =>  33.76186609268189
          ],
          [
            "lat" => -13.948686745220073,
            "lng" =>  33.760514259338386
          ],
          [
            "lat" => -13.948395196040217,
            "lng" =>  33.75940918922425
          ],
          [
            "lat" => -13.948405608517271,
            "lng" =>  33.75909805297852
          ],
          [
            "lat" => -13.948915819317165,
            "lng" =>  33.75894784927369
          ],
          [
            "lat" => -13.950560981149009,
            "lng" =>  33.75870108604432
          ],
          [
            "lat" => -13.95203953430219,
            "lng" =>  33.75836849212647
          ],
          [
            "lat" => -13.953039114162134,
            "lng" =>  33.758271932601936
          ],
          [
            "lat" => -13.954298995105088,
            "lng" =>  33.758325576782234
          ],
          [
            "lat" => -13.955121558287413,
            "lng" =>  33.75849723815919
          ],
          [
            "lat" => -13.956287718782445,
            "lng" =>  33.758786916732795
          ],
          [
            "lat" => -13.957589230226732,
            "lng" =>  33.75943064689637
          ],
          [
            "lat" => -13.959182270233176,
            "lng" =>  33.760267496109016
          ],
          [
            "lat" => -13.96086900647497,
            "lng" =>  33.76115798950196
          ],
          [
            "lat" => -13.961931019257138,
            "lng" =>  33.76189827919007
          ],
          [
            "lat" => -13.962420376441036,
            "lng" =>  33.76243472099305
          ]
        ],
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
        "coordinates" => [
          [
            "lat" => -13.95626689453962,
            "lng" =>  33.76994490623475
          ],
          [
            "lat" => -13.955600517775936,
            "lng" =>  33.76899003982545
          ],
          [
            "lat" => -13.954111575235368,
            "lng" =>  33.76783132553101
          ],
          [
            "lat" => -13.952695509073964,
            "lng" =>  33.76659750938416
          ],
          [
            "lat" => -13.951945823468192,
            "lng" =>  33.76586794853211
          ],
          [
            "lat" => -13.951050362467477,
            "lng" =>  33.764816522598274
          ],
          [
            "lat" => -13.950581805907117,
            "lng" =>  33.76416206359864
          ],
          [
            "lat" => -13.949832113430698,
            "lng" =>  33.763046264648445
          ],
          [
            "lat" => -13.94909283096367,
            "lng" =>  33.76164078712464
          ],
          [
            "lat" => -13.948488908316774,
            "lng" =>  33.75990271568299
          ],
          [
            "lat" => -13.948395196040217,
            "lng" =>  33.759280443191535
          ],
          [
            "lat" => -13.948416020993868,
            "lng" =>  33.759076595306404
          ],
          [
            "lat" => -13.946854144254338,
            "lng" =>  33.75914096832276
          ],
          [
            "lat" => -13.945073591873577,
            "lng" =>  33.759795427322395
          ],
          [
            "lat" => -13.944552959926282,
            "lng" =>  33.75992417335511
          ],
          [
            "lat" => -13.943949025395804,
            "lng" =>  33.759763240814216
          ],
          [
            "lat" => -13.94351169250773,
            "lng" =>  33.75984907150269
          ],
          [
            "lat" => -13.9433555019897,
            "lng" =>  33.76009583473206
          ],
          [
            "lat" => -13.942678675190043,
            "lng" =>  33.76038551330567
          ],
          [
            "lat" => -13.941876893181975,
            "lng" =>  33.760267496109016
          ],
          [
            "lat" => -13.941397905198777,
            "lng" =>  33.76014947891236
          ],
          [
            "lat" => -13.941481207528142,
            "lng" =>  33.762520551681526
          ],
          [
            "lat" => -13.941720701557546,
            "lng" =>  33.764054775238044
          ],
          [
            "lat" => -13.942303816416093,
            "lng" =>  33.76584649085999
          ],
          [
            "lat" => -13.942715119760576,
            "lng" =>  33.767257332801826
          ],
          [
            "lat" => -13.94366788292004,
            "lng" =>  33.769011497497566
          ],
          [
            "lat" => -13.944917402412734,
            "lng" =>  33.77047061920167
          ],
          [
            "lat" => -13.946000313833238,
            "lng" =>  33.771758079528816
          ],
          [
            "lat" => -13.947208170575388,
            "lng" =>  33.77321720123292
          ],
          [
            "lat" => -13.948061996526343,
            "lng" =>  33.774504661560066
          ],
          [
            "lat" => -13.948332721168018,
            "lng" =>  33.77592086791993
          ],
          [
            "lat" => -13.949061593624158,
            "lng" =>  33.77619981765748
          ],
          [
            "lat" => -13.950227784763578,
            "lng" =>  33.77635002136231
          ],
          [
            "lat" => -13.95093582650729,
            "lng" =>  33.77613544464112
          ]
        ],
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
        "coordinates" => [
          [
            "lat" => -13.961858136183404,
            "lng" =>  33.74170660972596
          ],
          [
            "lat" => -13.962003902307831,
            "lng" =>  33.742049932479866
          ],
          [
            "lat" => -13.961806076830904,
            "lng" =>  33.743584156036384
          ],
          [
            "lat" => -13.961441661034126,
            "lng" =>  33.74563336372376
          ],
          [
            "lat" => -13.96121259938121,
            "lng" =>  33.747038841247566
          ],
          [
            "lat" => -13.960754475392395,
            "lng" =>  33.74893784523011
          ],
          [
            "lat" => -13.96020264301496,
            "lng" =>  33.75089049339295
          ],
          [
            "lat" => -13.959806988799023,
            "lng" =>  33.752328157424934
          ],
          [
            "lat" => -13.959515453679014,
            "lng" =>  33.753315210342414
          ],
          [
            "lat" => -13.958869910315313,
            "lng" =>  33.754774332046516
          ],
          [
            "lat" => -13.95797447620486,
            "lng" =>  33.75673770904542
          ],
          [
            "lat" => -13.957079038616182,
            "lng" =>  33.758518695831306
          ],
          [
            "lat" => -13.95681873633908,
            "lng" =>  33.759055137634284
          ],
          [
            "lat" => -13.95613153691538,
            "lng" =>  33.758786916732795
          ],
          [
            "lat" => -13.954642597803936,
            "lng" =>  33.75834703445435
          ],
          [
            "lat" => -13.953153649077748,
            "lng" =>  33.75825047492982
          ],
          [
            "lat" => -13.951654278407815,
            "lng" =>  33.75847578048707
          ],
          [
            "lat" => -13.949623865088146,
            "lng" =>  33.75886201858521
          ],
          [
            "lat" => -13.948384783562693,
            "lng" =>  33.75908732414246
          ],
          [
            "lat" => -13.947583021382012,
            "lng" =>  33.75908732414246
          ],
          [
            "lat" => -13.946375166602328,
            "lng" =>  33.759280443191535
          ],
          [
            "lat" => -13.944917402412734,
            "lng" =>  33.75987052917481
          ],
          [
            "lat" => -13.944500896666945,
            "lng" =>  33.75990271568299
          ],
          [
            "lat" => -13.944042739478276,
            "lng" =>  33.759773969650276
          ],
          [
            "lat" => -13.943542930598642,
            "lng" =>  33.75984907150269
          ],
          [
            "lat" => -13.943397152804849,
            "lng" =>  33.760031461715705
          ],
          [
            "lat" => -13.942668262454523,
            "lng" =>  33.76037478446961
          ],
          [
            "lat" => -13.941866480410312,
            "lng" =>  33.760267496109016
          ],
          [
            "lat" => -13.941356254022828,
            "lng" =>  33.76014947891236
          ],
          [
            "lat" => -13.941325015635933,
            "lng" =>  33.75743508338929
          ],
          [
            "lat" => -13.9412469196502,
            "lng" =>  33.754822611808784
          ],
          [
            "lat" => -13.94114799803033,
            "lng" =>  33.75115871429444
          ],
          [
            "lat" => -13.941231300449882,
            "lng" =>  33.75015020370484
          ],
          [
            "lat" => -13.941876893181975,
            "lng" =>  33.74997854232789
          ],
          [
            "lat" => -13.943043120636514,
            "lng" =>  33.7502145767212
          ],
          [
            "lat" => -13.943772009802853,
            "lng" =>  33.750300407409675
          ],
          [
            "lat" => -13.94487575187198,
            "lng" =>  33.75015020370484
          ],
          [
            "lat" => -13.945792062031709,
            "lng" =>  33.74991416931153
          ],
          [
            "lat" => -13.948770044918064,
            "lng" =>  33.74834775924683
          ],
          [
            "lat" => -13.95245602643704,
            "lng" =>  33.74635219573975
          ],
          [
            "lat" => -13.956141949043149,
            "lng" =>  33.744313716888435
          ],
          [
            "lat" => -13.958432605719189,
            "lng" =>  33.74313354492188
          ]
        ],
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
