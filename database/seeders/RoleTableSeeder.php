<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate([
            "name"=>"management"
        ]);

        Role::updateOrCreate([
            "name"=>"administrator"
        ]);

        Role::updateOrCreate([
            "name"=>"accountant"
        ]);

        Role::updateOrCreate([
            "name"=>"employee"
        ]);

        Role::updateOrCreate([
            "name"=>"unverified"
        ]);

        Role::updateOrCreate([
            "name"=>"disabled"
        ]);

        Role::updateOrCreate([
            "name"=>"oss"
        ]);

        Role::updateOrCreate([
            "name"=>"operations"
        ]);

        Role::updateOrCreate([
            "name"=>"delivery"
        ]);
        Role::updateOrCreate([
            "name"=>"production"
        ]);
    }
}
