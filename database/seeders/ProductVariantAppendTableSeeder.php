<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductVariantAppendTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variant = \App\Models\ProductVariant::find(1); // Thanthwe (42.5)
        $variant->update([
            'slug' => Str::slug("Thanthwe Shayona Cement 42.5N Strength"),
            'name' => "Thanthwe Shayona Cement 42.5R Strength",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => false,
            'specifications' => json_encode([
                [
                    "label" =>  "Weight",
                    "value" => "50kg",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" =>  "Manufacturer",
                    "value" => "Shayona Cement",
                ]
            ]),
            'about' => "<ul> 
            <li>High strength concrete (structural applications and suspended slabs, water tight concrete, and heavy duty industrial floors) - 1 Cement, 2 Sand, 2 Stone</li> 
            <li>High strength concrete (floors, reinforced concrete and suspended slabs) - 1 Cement, 2 Sand, 3 Stone</li> 
            <li>Medium strength concrete (driveways, light industrial unreinforced applications) - 1 Cement, 3 Sand, 3 Stone</li> 
            <li>Low strength concrete (foundations, footings and reinforced domestic floors) - 1 Cement, 3 Sand, 4 Stone</li> 
            <li>Mortor for bricklaying and plastering - 1 Cement, 6 Sand</li> 
            <li>Brick and block making - 1 Cement, 10 Sand</li> 
            </ul>",
            'description_full' => "
            <p>Shayona Cement is becoming one of the
            most trusted and preferred cement brands in
            Malawi for engineers, builders, contractors
            and individual home builders.</p>
            <p>Shayona has now set new standards by
            pioneering the 42.5R grade Thanthwe Cement,
            specifically used in vital structures such as
            dams, bridges, flyovers, airports, railways and
            high-rise buildings.</p>
            <p>Thanthwe 42.5R is the cement of choice for vital infrastructure projects including dams, bridges, flyovers, airports, railways, and high-rise buildings. Its superior grade 42.5R specification ensures exceptional strength and durability that meets the rigorous demands of modern engineering.
            </p>",
            'link' => "https://www.shayonacement.com/our-products/thanthwe-cement",

        ]);
        $variant = \App\Models\ProductVariant::find(2); //Akshar (32.5)
        $variant->update([
            'slug' => Str::slug("Akshar (Shayona Cement) 32.5R Strength"),
            'name' => "Akshar (Shayona Cement) 32.5N Strength",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => false,
            'specifications' => json_encode([
                [
                    "label" =>  "Weight",
                    "value" => "50kg",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" =>  "Manufacturer",
                    "value" => "Shayona Cement",
                ]
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(3); //Quarry Stone 20 Tonnes
        $variant->update([
            'slug' => Str::slug("Quarry Stone 19/20mm Concrete Aggregate 20 Tonnes"),
            'name' => "Quarry Stone 19/20mm Concrete Aggregate 20 Tonnes",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                [
                    "label" => "Measurement",
                    "value" => "Tonnage",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" => "Source",
                    "value" => "Nathenje",
                ]
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(4); //Quarry Stone 25 Tonnes
        $variant->update([
            'slug' => Str::slug("Quarry Stone 19/20mm Concrete Aggregate 25 Tonnes"),
            'name' => "Quarry Stone 19/20mm Concrete Aggregate 25 Tonnes",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                [
                    "label" => "Measurement",
                    "value" => "Tonnage",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" => "Source",
                    "value" => "Nathenje",
                ]
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(5); //Pebble Stone 20 Tonnes
        $variant->update([
            'slug' => Str::slug("Pebble Stone 10/14mm Stone 20 Tonnes"),
            'name' => "Pebble Stone 10/14mm Stone 20 Tonnes",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                [
                    "label" => "Measurement",
                    "value" => "Tonnage",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" => "Source",
                    "value" => "Nathenje",
                ]
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(6); //Pebble Stone 25 Tonnes
        $variant->update([
            'slug' => Str::slug("Pebble Stone 10/14mm Stone 25 Tonnes"),
            'name' => "Pebble Stone 10/14mm Stone 25 Tonnes",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                [
                    "label" => "Measurement",
                    "value" => "Tonnage",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" => "Source",
                    "value" => "Nathenje",
                ]
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(7); //Quarry Dust 20 Tonnes
        $variant->update([
            'slug' => Str::slug("Quarry Dust 4mm Stone 20 Tonnes"),
            'name' => "Quarry Dust 4mm Stone 20 Tonnes",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                [
                    "label" => "Measurement",
                    "value" => "Tonnage",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" => "Source",
                    "value" => "Nathenje",
                ]
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(8); //Quarry Dust 25 Tonnes
        $variant->update([
            'slug' => Str::slug("Quarry Dust 4mm Stone 25 Tonnes"),
            'name' => "Quarry Dust 4mm Stone 25 Tonnes",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                [
                    "label" => "Measurement",
                    "value" => "Tonnage",
                ]
            ]),
            'product_information' => json_encode([
                [
                    "label" => "Source",
                    "value" => "Nathenje",
                ]
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(9); //Medium
        $variant->update([
            'slug' => Str::slug("Cement Blocks Medium 150mm x 200mm x 400mm"),
            'name' => "Cement Blocks Medium 150mm x 200mm x 400mm",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => false,
            'specifications' => json_encode([
                [
                    "label" => "Width",
                    "value" => "150mm",
                ],
                [
                    "label" => "Length",
                    "value" => "400mm",
                ],
                [
                    "label" => "Height",
                    "value" => "200mm",
                ],
            ]),
            'product_information' => json_encode([
                // "Manufacturer" => "Shayona Cement",
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(10); //Big
        $variant->update([
            'slug' => Str::slug("Cement Blocks Big 200mm x 200mm x 400mm"),
            'name' => "Cement Blocks Big 200mm x 200mm x 400mm",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => false,
            'specifications' => json_encode([
                [
                    "label" => "Width",
                    "value" => "200mm",
                ],
                [
                    "label" => "Length",
                    "value" => "400mm",
                ],
                [
                    "label" => "Height",
                    "value" => "200mm",
                ],
            ]),
            'product_information' => json_encode([
                // "Manufacturer" => "Shayona Cement",
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(11); //River Sand Normal
        $variant->update([
            'slug' => Str::slug("River Sand Normal Locally Sourced Fine/Coarse"),
            'name' => "River Sand Normal Locally Sourced Fine/Coarse",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                // "Sour" => "50kg",
            ]),
            'product_information' => json_encode([
                // "Source" => "Locally Mined",
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
        $variant = \App\Models\ProductVariant::find(12); //River Sand Salima
        $variant->update([
            'slug' => Str::slug("River Sand Salima Quality Fine/Coarse"),
            'name' => "River Sand Salima Quality Fine/Coarse",
            'cost_original' => $variant->cost + ($variant->cost * 0.1),
            'transport_inclusive' => true,
            'specifications' => json_encode([
                // "Weight" => "50kg",
            ]),
            'product_information' => json_encode([
                //  "Source" => "Locally Mined",
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);


        \App\Models\ProductVariant::updateOrCreate([
            "description" => "Transportation",
            'slug' => Str::slug("transportation"),
            "unit" => "Delivery",
            "quantity" => 1,
            "cost" => 100000,
            "cost_original" => 100000,
            "product_id" => 8,
            'name' => "Transportation",
            'transport_inclusive' => false,
            'specifications' => json_encode([
                // "Weight" => "50kg",
            ]),
            'product_information' => json_encode([
                //  "Source" => "Locally Mined",
            ]),
            'about' => null,
            'description_full' => null,
            'link' => null,
        ]);
    }
}
