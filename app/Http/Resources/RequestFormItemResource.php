<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestFormItemResource extends JsonResource
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
            "id"                => intval($this->id),
            "details"           => $this->details,
            "units"             => $this->units,
            "quantity"          => floatval($this->quantity),
            "unitCost"          => floatval($this->unit_cost),
            "totalCost"         => floatval($this->total_cost),
            "balance"           => floatval($this->balance),
            "status"            => intval($this->status),
            "requestFormId"     => $this->request_id,
            "accountId"         => $this->accounting_account_id,
            "transporterId"     => $this->transporter_id,
            "supplierId"        => $this->supplier_id,
            "comments"          => $this->comments,
            "account"           => new AccountingAccountResource($this->account),
        ];
    }
}
