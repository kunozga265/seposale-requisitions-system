<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->receipt == null){
            dd($this->receipt_id);
        }
        return [
            "id" => intval($this->id),
            "name" => $this->name,
            "date" => intval($this->receipt->date),
            "balance" => floatval($this->balance),
            "amount" => floatval($this->amount),
            "cost" => floatval($this->cost) ?? null,
            "units" => $this->units ?? null,
            "summary" => $this->summary,
            "siteSaleSummary" => $this->siteSaleSummary,
        ];
    }
}
