<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierVoucherResource extends JsonResource
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
            "serial"                => $this->serial,
            "code"                  => $this->formattedCode(),
            "name"                  => $this->getName(),
            "date"                  => intval($this->date),
            "amount"                => floatval($this->amount),
            "details"              => $this->details,
            // "balance"                => floatval($this->balance),
            "payable"               => new PayableResource($this->payable),
            "site"                  => $this->site,
            "transporter"           => new TransporterResource($this->transporter),
            "supplier"              => new SupplierResource($this->supplier),
            "paid"                  => $this->paid == 1,
            "requestForm"           => new RequestFormResource($this->requestForm),
            "requestFormItem"           => new RequestFormItemResource($this->requestFormItem),
            "requestFormPayout"           => new RequestFormResource($this->payoutRequestForm),
            "requestFormItemPayout"           => new RequestFormItemResource($this->payoutRequestFormItem),
        ];
    }
}
