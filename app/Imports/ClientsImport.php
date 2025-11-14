<?php

namespace App\Imports;

use App\Models\Client;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NotificationController;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection($rows)
    {
        foreach ($rows as $row) {
            $phone_number = $row['phone_number'];
            if ($phone_number != '' && $phone_number != null) {

                $phone_number_other = null;
                if (isset($row['phone_number_other'])) {
                    $phone_number_other = $row['phone_number_other'];
                }

                $email = null;
                if (isset($row['email'])) {
                    $email = $row['email'];
                }

                $client = Client::where('phone_number', $phone_number)->first();

                if (!is_object($client)) {
                    $client = Client::create([
                        'serial' => (new AppController())->generateUniqueCode("CLIENT"),
                        'name' => ucwords($row['name']),
                        'phone_number' => (new ClientController())->cleanPhoneNumber($phone_number),
                        'phone_number_other' => (new ClientController())->cleanPhoneNumber($phone_number_other),
                        'email' => $email,
                        'organisation' => false,
                        'client_type_id' => 7,
                    ]);
                }

                $message = "Quality products and services are guaranteed.";
                (new NotificationController())->processWhatsappMessage("pricelist_referred", $client->serial, $message);
            }
        }
    }
}
