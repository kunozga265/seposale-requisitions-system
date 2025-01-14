<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = Client::all();
        //
        foreach ($clients as $client){

            if($client->phone_number != null){
                //remove every space
                $phone_number = trim(str_replace(" ","",$client->phone_number));
                $phone_number = trim(str_replace("+","",$phone_number));
                if($phone_number[0] === "0"){
                    $phone_number[0] = "-";
                    $phone_number = str_replace("-","",$phone_number);
                    $phone_number = "265{$phone_number}";
                }
                dump("(1){$client->phone_number}", "(2)$phone_number", "id:{$client->id}");

            $client->update([
                "phone_number" => $phone_number
            ]);
            }


        }

    }
}
