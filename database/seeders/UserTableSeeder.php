<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::create([
           'firstName'      =>  'Kunozga',
           'middleName'     =>  '',
           'lastName'       =>  'Mlowoka',
           'email'          =>  'kunozgamlowoka@gmail.com',
           'position_id'    =>  2,
           'password'       =>  bcrypt('12345678'),
        ]);
        $user->roles()->attach([1]);
    }
}
