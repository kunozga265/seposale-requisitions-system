<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
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
            "id"                    => $this->id,
            "code"                  => (new AppController())->getZeroedNumber($this->code),
            "paymentMethod"         => $this->paymentMethod->name,
            "amount"                => $this->amount,
            "client"                => $this->client,
            "reference"             => $this->reference,
            "information"           => json_decode($this->information),
            'generatedBy'           => new UserResource($this->user),
            "date"                  => $this->date,
        ];
    }
}
