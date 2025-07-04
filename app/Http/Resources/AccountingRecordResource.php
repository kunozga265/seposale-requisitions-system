<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountingRecordResource extends JsonResource
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
            "serial" => $this->serial,
            "reference" => $this->reference,    
            "date" => intval($this->date),
            "name" => $this->name,
            "description" => $this->description,
            "amount" => floatval($this->amount),
            "openingBalance" => floatval($this->opening_balance),
            "closingBalance" => floatval($this->closing_balance),
            "type" => strtoupper($this->type), // 'debit' or 'credit'
            "accountingAccount" => new AccountingAccountResource($this->whenLoaded('accountingAccount', $this->accountingAccount)),
            "receipt" => new ReceiptResource($this->whenLoaded('receipt',$this->receipt), ),
        ];
    }
}
