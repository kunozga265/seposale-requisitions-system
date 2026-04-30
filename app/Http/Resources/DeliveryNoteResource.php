<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => intval($this->id),
            "serial" => $this->serial,
            "code" => intval($this->code),
            "date"  => floatval($this->date),
            "quantity" => floatval($this->quantity),
            "cost" => floatval($this->cost),
            "total" => floatval($this->total),
            "balance" => floatval($this->balance),
            "photo" => $this->photo,
            "recipientName" => $this->recipient_name,
            "recipientPhoneNumber" => $this->recipient_phone_number,
            "summary" => new SummaryResource($this->delivery->summary),
            "location" => $this->delivery->summary->sale->location,
            "delivery" => [
                "id" => intval($this->delivery->id),
                "status" => intval($this->delivery->status),
                "code" => (new AppController())->getZeroedNumber($this->delivery->code),
                "costs" => floatval($this->delivery->costs()),
            ],
        ];
    }
}
