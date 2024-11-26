<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        \App\Models\Client::factory(5)->create();

//         $this->call(RoleTableSeeder::class);
//         $this->call(PositionTableSeeder::class);
//         $this->call(ProductTableSeeder::class);
//
//         $this->call(UserTableSeeder::class);
//         $this->call(PaymentMethodTableSeeder::class);

//         $this->call(SummaryTableSeeder::class);
//         $this->call(RequestFormTableSeeder::class);
//         $this->call(DeliveryTableSeeder::class);
//         $this->call(SitesTableSeeder::class);
         $this->call(InventoryTableSeeder::class);
    }
}
