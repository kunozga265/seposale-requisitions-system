<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
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
            "id"                => $this->id,
            "product"           => $this->product,
            "variant"           => $this->variant,
            "date"           => $this->date,
            "amount"           => $this->amount,
            "quantity"           => $this->quantity,
            "delivery"           => $this->delivery,
            "overdue"           => $this->delivery->overdue()
        ];
    }
}
