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
        return [
            "id" => $this->id,
            "name" => $this->name,
            "balance" => $this->balance,
            "amount" => $this->amount,
            "cost" => $this->cost ?? null,
            "units" => $this->units ?? null,
            "summary" => $this->summary,
            "site_sale_summary" => $this->siteSaleSummary,
        ];
    }
}
