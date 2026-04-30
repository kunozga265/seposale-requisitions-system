<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            "id" => $this->id,
            "date" => floatval($this->date),
            "reference" => $this->reference,
            "description" => $this->description,
            "fromTo" => $this->from_to,
            "expense" => $this->expense,
            "receipt" => $this->receipt,
            "account" => $this->account,
            "total" => floatval($this->total),
            "balance" => floatval($this->balance),
            "type" => $this->type,
        ];
    }
}
