<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
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
            "code"                  => $this->formattedCode(),
            "payee"                 => $this->payee(),
            "description"           => $this->description,
            "total"                 => floatval($this->total),
            "date"                  => $this->date,
            "contents"              => json_decode($this->contents),
            "expenseType"           => $this->expenseType,
            "sale"                  => $this->sale,
            "delivery"              => $this->delivery,
            "requestForm"           => new RequestFormResource($this->requestForm),
            "transporter"           => $this->transporter,
            "supplier"              => $this->supplier,
        ];
    }
}
