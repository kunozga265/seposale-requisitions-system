<?php

namespace Database\Seeders;

use App\Http\Controllers\CollectionController;
use App\Models\Delivery;
use Illuminate\Database\Seeder;

class SyncCollections extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveries = Delivery::whereNotNull('site_sale_summary_id')->get();
        foreach ($deliveries as $delivery) {
            foreach ($delivery->deliveryNotes as $note) {

                (new CollectionController())->recordCollection(
                    null,
                    $delivery->siteSaleSummary,
                    $note->quantity,
                    $note->photo,
                    $note->recipient_name,
                    $note->recipient_phone_number,
                );
            }
        }
    }
}
