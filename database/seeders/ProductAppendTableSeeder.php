<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductAppendTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = \App\Models\Product::find(1); // Cement
        $product->update([
            'slug' => Str::slug($product->name),
            'description' => 'Supaset, Duracrete, Sinoma, Thanthwe & Akshar',
            'description_full' => 'At Seposale Limited, we supply only premium-grade cement essential for critical load-bearing applications. Whether you are pouring deep substructure foundations or casting superstructure beams and columns, our cement ensures rapid setting and long-term durability. It is the perfect binding agent for high-strength concrete and smooth mortar finishes.',
        ]);
        $product = \App\Models\Product::find(2); // Quarry Stone
        $product->update([
            'slug' => Str::slug($product->name),
            'description' => 'Concrete aggregate (19/20mm) ',
            'description_full' => 'Our quarry stone is crushed to precision sizes to meet the rigorous demands of reinforced concrete works. Ideal for casting robust foundation footings, floor slabs, and lintels, Seposale’s clean and hard-wearing aggregates ensure your project achieves maximum compressive strength and structural stability from the ground up.',
        ]);
        $product = \App\Models\Product::find(3); // Pebble Stone
        $product->update([
            'slug' => Str::slug($product->name),
            'description' => 'Available in 10mm and 14mm',
            'description_full' => 'Add aesthetic value and functionality to your project with our carefully sorted pebble stones. While excellent for drainage solutions in substructure works, they are perfect for superstructure finishes, including driveway paving, landscaping, and decorative concrete cladding. Seposale Limited delivers nature’s durability right to your site.',
        ]);
        $product = \App\Models\Product::find(4); // Quarry Dust
        $product->update([
            'slug' => Str::slug($product->name),
            'description' => 'Finely crushed stone',
            'description_full' => 'An excellent alternative to river sand for specific applications, our quarry dust is vital for achieving high-density compaction in substructure blinding and slab preparation. It is also the secret ingredient for producing stronger, smoother cement blocks and pavers, providing a reliable base for all your construction needs.',
        ]);
        $product = \App\Models\Product::find(5); // Cement Blocks
        $product->update([
            'slug' => Str::slug($product->name),
            'description' => 'Avaialable sizes; Medium (150mm) and Big (200mm)',
            'description_full' => 'Seposale Limited supplies high-quality, perfectly cured cement blocks designed for speed and stability. From load-bearing foundation walls to partitioning superstructures, our blocks offer uniform dimensions and exceptional strength, minimizing mortar usage and ensuring straight, sturdy walls every time.',
        ]);
        $product = \App\Models\Product::find(6); // River Sand
        $product->update([
            'slug' => Str::slug($product->name),
            'description' => 'Fine, medium & coarse',
            'description_full' => 'The quality of your plaster and mortar depends on the purity of your sand. Our river sand is sourced and screened to remove impurities, ensuring excellent bonding properties for masonry works. It is the ideal choice for mixing concrete and achieving smooth, crack-free finishes on your building’s superstructure.',
        ]);
    }
}
