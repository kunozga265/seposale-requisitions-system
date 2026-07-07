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
        Brand::updateOrCreate([
            "name" => "ASUM",
        ], [
            "file" => "files/brands/asum.png",
            "description" => "ASUM",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "China Civil Engineering Construction Corporation",
        ], [
            "file" => "files/brands/ccecc.png",
            "description" => "CCECC",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Jehovah's Witnesses",
        ], [
            "file" => "files/brands/jw.svg",
            "description" => "JW",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Assemblies of God",
        ], [
            "file" => "files/brands/mag.jpeg",
            "description" => "AG",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Smart Realtors",
        ], [
            "file" => "files/brands/sr.jpeg",
            "description" => "SR",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Mota Engil",
        ], [
            "file" => "files/brands/mota-engil.png",
            "description" => "ME",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Hisco",
        ], [
            "file" => "files/brands/Hisco.jpg",
            "description" => "Hisco",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Mount Sinai",
        ], [
            "file" => "files/brands/Mount Sinai.jpg",
            "description" => "Mount Sinai",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Smaac Trust",
        ], [
            "file" => "files/brands/Smaac Trust.jpg",
            "description" => "Smaac Trust",
            "link" => null
        ]);
        Brand::updateOrCreate([
            "name" => "Steecon",
        ], [
            "file" => "files/brands/Steecon.png",
            "description" => "Steecon",
            "link" => null
        ]);
    }
}
