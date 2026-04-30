<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create([
            "name" => "ASUM",
            "file" => "files/brands/asum.png",
            "description" => "",
            "link" => null
        ]);
        Brand::create([
            "name" => "CCECC",
            "file" => "files/brands/ccecc.png",
            "description" => "China Civil Engineering Construction Corporation",
            "link" => null
        ]);
        Brand::create([
            "name" => "JW",
            "file" => "files/brands/jw.svg",
            "description" => "Jehovah's Witnesses",
            "link" => null
        ]);
        Brand::create([
            "name" => "AG",
            "file" => "files/brands/mag.jpeg",
            "description" => "Assemblies of God",
            "link" => null
        ]);
        Brand::create([
            "name" => "SR",
            "file" => "files/brands/sr.jpeg",
            "description" => "Smart Realtors",
            "link" => null
        ]);
        Brand::create([
            "name" => "ME",
            "file" => "files/brands/mota-engil.png",
            "description" => "Mota Engil",
            "link" => null
        ]);
    }
}
