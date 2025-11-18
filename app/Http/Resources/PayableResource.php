<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class PayableResource extends JsonResource
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
            "id"                    => intval($this->id),
            "code"                  => $this->formattedCode(),
            "payee"                 => $this->getName(),
            "description"           => $this->description,
            "total"                 => floatval($this->total),
            "date"                  => intval($this->date),
            "contents"              => json_decode($this->contents),
            // "expenseType"           => $this->expenseType,
            'sale' => $this->sale != null ? [
                'id' => $this->sale->id,
                'serial' => $this->sale->serial,
                'code' => (new AppController())->getZeroedNumber($this->sale->code_alt),
            ] : null,
            "delivery" => $this->delivery != null && $this->delivery?->status != 0 ? [
                "id" => intval($this->delivery->id),
                "status" => intval($this->delivery->status),
                "code" => (new AppController())->getZeroedNumber($this->delivery->code),
                "costs" => floatval($this->delivery->costs()),
            ] : null,
            "requestForm"           => new RequestFormResource($this->requestForm),
            "transporter"           => $this->transporter,
            "supplier"              => $this->supplier,
        ];
    }
}
