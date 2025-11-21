<?php

namespace App\Imports;

use App\Models\Client;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NotificationController;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Referral;

class ClientsImport implements ToCollection, WithHeadingRow
{
    public $user_id;
    public $referred_by_id;

    public function __construct($user_id, $referred_by_id)
    {
        $this->user_id = $user_id;
        $this->referred_by_id = $referred_by_id;
    }


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

                $client = (new ClientController())->getOrCreate($row['name'], $phone_number, $phone_number_other, $email);

                //send the pricelist
                if ($this->user_id != null) {

                    $name = User::findOrFail($this->user_id)->first()->fullName();
                    $message = "You have been referred to us by {$name}.";

                    Referral::create([
                        'date' => Carbon::now()->getTimestamp(),
                        'referred_by_id' => $this->referred_by_id,
                        'client_id' => $client->id,
                        'user_id' => $this->user_id,
                    ]);
                } else {
                    $message = "Quality products and services are guaranteed.";
                }

                (new NotificationController())->processWhatsappMessage("pricelist_referred", $client->serial, $message);
            }
        }
    }
}
