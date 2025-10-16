<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentReceiptResource extends JsonResource
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
            'date' => intval($this->date),
            'amount' => floatval($this->amount),
            // 'path' => $this->path,
            'description' => $this->description,
            'file' => $this->file,
            'type' => $this->getType(),
            'paymentMethod' => $this->paymentMethod,
            'active' => $this->active == 1,
            'sale' => $this->sale != null ? [
                'id' => $this->sale->id,
                'serial' => $this->sale->serial,
                'code' => (new AppController())->getZeroedNumber($this->sale->code_alt),
            ] : null,
            'siteSale' => $this->siteSale != null ? [
                'id' => $this->siteSale->id,
                'serial' => $this->siteSale->serial,
                'code' => (new AppController())->getZeroedNumber($this->siteSale->code),
            ] : null,
            'user' => new UserResource($this->user),
        ];
    }
}
