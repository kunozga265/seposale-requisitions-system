<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            "status" => strval($this->status),
            "reference" => strval($this->reference),
            "content" => json_decode($this->content),
            "client" => $this->client,
            "sale" => [
                "id" => intval($this->sale->id),
                "serial" => $this->sale->serial,
                "code" => "OSS" . (new AppController())->getZeroedNumber($this->sale->code),
                'client' => $this->sale->client,
                'date' => intval($this->sale->date),
            ],
            "receipt" => $this->receipt,
        ];
    }
}
