<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialsType;
use Illuminate\Database\Seeder;

class MaterialsTypeSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaterialsType::create([ 
            "name"=> "Cement",
            "slug"=> "cement",
        ]);
        MaterialsType::create([ 
            "name"=> "Pebble Stone",
            "slug"=> "pebble-stone",
        ]);
        MaterialsType::create([ 
            "name"=> "Quarry Dust",
            "slug"=> "quarry-dust",
        ]);
        MaterialsType::create([ 
            "name"=> "River Sand",
            "slug"=> "river-sand",
        ]);
        MaterialsType::create([ 
            "name"=> "Other",
            "slug"=> "other",
        ]);
    }
}
