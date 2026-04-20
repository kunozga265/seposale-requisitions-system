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
use App\Models\WhatsappMessage;

class ClientsImport implements ToCollection, WithHeadingRow
{
    public $type;
    public $user_id;
    public $referred_by_id;
    public $template;
    public $template_file;
    public $force_send;

    public function __construct($type, $content)
    {
        $this->type = $type;
        $this->user_id = $content["user_id"];
        $this->referred_by_id = $content["referred_by_id"];
        if (array_key_exists('template', $content)) {
            $this->template = $content["template"];
        }
        if (array_key_exists('template_file', $content)) {
            $this->template_file = $content["template_file"];
        }
        if (array_key_exists('force_send', $content)) {
            $this->force_send = $content["force_send"];
        }
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
                $alias = null;
                if (isset($row['alias'])) {
                    $alias = $row['alias'];
                }
                $organisation = false;
                if (isset($row['organisation'])) {
                    $organisation = $row['organisation'] == 1 || $row['organisation'] == '1' || $row['organisation'] == true || strtolower($row['organisation']) == 'true';
                }

                $address = null;
                if (isset($row['address'])) {
                    $address = $row['address'];
                }

                $client_type = null;
                if (isset($row['type'])) {
                    $client_type = $row['type'];
                }

                $client = (new ClientController())->getOrCreate(
                    name: $row['name'],
                    phone_number: $phone_number,
                    phone_number_other: $phone_number_other,
                    email: $email,
                    alias: $alias,
                    address: $address,
                    organisation: $organisation,
                    client_type: $client_type,
                );


                //send the pricelist
                if ($this->user_id != null) {

                    $name = User::find($this->referred_by_id)->fullName();
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

                if ($this->type == "PRICELIST_SEND") {
                    if (!WhatsappMessage::where('client_id', $client->id)->where('message_type', 'pricelist_referred')->exists() || $this->force_send) {
                        (new NotificationController())->processWhatsappMessage("pricelist_referred", $client->serial, $message);
                    }
                } else if ($this->type == "BATCH_SEND") {
                    if (!WhatsappMessage::where('client_id', $client->id)->where('message_type', $this->template->code)->exists() || $this->force_send) {
                        (new NotificationController())->processWhatsappTemplateMessage($this->template, $client->serial, $message, $this->template_file);
                    }
                }
            }
        }
    }
}
