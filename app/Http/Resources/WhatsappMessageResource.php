<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WhatsappMessageResource extends JsonResource
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
            "name" => $this->name,
            "phoneNumber" => $this->phone_number,
            "type" => $this->type,
            "messageType" => $this->message_type,
            "message" => $this->message,
            "wamid" => $this->wamid,
            "payload" => json_decode($this->payload),
            "client" => $this->client,
            "sale" => $this->sale,
            "quotation" => $this->quotation,
            "invoice" => $this->invoice,
            "receipt" => $this->receipt,
            "delivery" => $this->delivery,
            "collection" => $this->collection,
            "creditVoucher" => $this->creditVoucher,
            "supplierVoucher" => $this->supplierVoucher,
            "requestFormItem" => $this->requestFormItem,
            "user" => $this->user,
            "status" => $this->status,
            "statuses" => $this->statuses,
            "date" => $this->created_at->getTimestamp(),
        ];
    }
}
