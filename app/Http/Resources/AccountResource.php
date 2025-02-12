<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            "name" => $this->name,
            "number" => $this->number,
            "branch" => $this->branch,
            "type" => $this->type,
            "balance" => $this->balance,
            "expenses" => ExpenseResource::collection($this->expenses),
            "receipts" => ReceiptResource::collection($this->receipts),
        ];
    }
}
