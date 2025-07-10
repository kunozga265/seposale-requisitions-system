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

        $alternateRecord = null;
        if($this->alternateRecord != null){
           $alternateRecord =[
             "id" => $this->alternateRecord->id,
             "serial" => $this->alternateRecord->serial,
              "openingBalance" => floatval($this->alternateRecord->opening_balance),
            "closingBalance" => floatval($this->alternateRecord->closing_balance),
             "account" => $this->alternateRecord->accountingAccount,
           ];
        }

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
            "alternateRecord" => $alternateRecord,
            "type" => strtoupper($this->type), // 'debit' or 'credit'
            "account" => $this->accountingAccount,
            "receiptCode" => $this->receipt?->code,
            "productionCode" => $this->production?->code,
            "requisitionCode" => $this->requestFormItem?->requestForm->code_alt,
            "collectionCode" => $this->collection?->code,
            "saleCode" => $this->summary?->sale->code,
            "siteSaleCode" => $this->siteSaleSummary?->sale->code,
            // "accountingAccount" => new AccountingAccountResource($this->whenLoaded('accountingAccount', $this->accountingAccount)),
            "receipt" => new ReceiptResource($this->whenLoaded('receipt',$this->receipt), ),
        ];
    }
}
