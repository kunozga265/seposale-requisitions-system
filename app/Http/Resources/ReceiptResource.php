<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiptResource extends JsonResource
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
            'serial'                => $this->serial,
            "code"                  => (new AppController())->getZeroedNumber($this->code),
            "date"                  => intval($this->date),
            "paymentMethod"         => $this->paymentMethod->name,
            "amount"                => floatval($this->amount),
            "client"                => $this->client,
            "reference"             => $this->reference,
            // "information"           => $this->information(),
            "summaries"             => ReceiptSummaryResource::collection($this->summaries), 
            'generatedBy'           => new UserResource($this->user),
            'sale'                  => $this->sale != null ? [
                "id" => $this->sale->id,
                "code" => (new AppController())->getZeroedNumber($this->sale->code_alt),
            ] : null,
           'whatsapp' => $this->whatsapp != null ? intval($this->whatsapp) == 1 : false ,
            'transaction'           => $this->transaction != null ? true : false ,
            'record'           => $this->record != null ? true : false ,
        ];
    }
}
