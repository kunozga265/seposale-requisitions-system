<?php

namespace Database\Seeders;

use App\Http\Controllers\AppController;
use App\Models\Client;
use App\Models\Collection;
use App\Models\Delivery;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Receipt;
use App\Models\SiteSale;
use Illuminate\Database\Seeder;

class SerialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //clients
        $objects = SiteSale::all();
        foreach ($objects as $obj){
            $obj->update([
                'serial' =>  (new AppController())->generateUniqueCode("SITESALE"),
            ]);
        }

        //invoices
        $objects = Invoice::all();
        foreach ($objects as $obj){
            $obj->update([
                'serial' =>  (new AppController())->generateUniqueCode("INVOICE"),
            ]);
        }

        //quotaTions
        $objects = Quotation::all();
        foreach ($objects as $obj){
            $obj->update([
                'serial' =>  (new AppController())->generateUniqueCode("QUOTATION"),
            ]);
        }

        //receipts
        $objects = Receipt::all();
        foreach ($objects as $obj){
            $obj->update([
                'serial' =>  (new AppController())->generateUniqueCode("RECEIPT"),
            ]);
        }

        //deliveries
        $objects = Delivery::all();
        foreach ($objects as $obj){
            $obj->update([
                'serial' =>  (new AppController())->generateUniqueCode("DELIVERY"),
            ]);
        }

        //collections
        $objects = Collection::all();
        foreach ($objects as $obj){
            $obj->update([
                'serial' =>  (new AppController())->generateUniqueCode("COLLECTION"),
            ]);
        }

        //clients
        $clients = Client::all();
        foreach ($clients as $client){
            $client->update([
                'serial' =>  (new AppController())->generateUniqueCode("CLIENT"),
            ]);
        }

    }
}
