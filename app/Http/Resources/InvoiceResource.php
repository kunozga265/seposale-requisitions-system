<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'id' => $this->id,
            'code' => (new AppController())->getZeroedNumber($this->code, $this->revision),
            'sale' => new SaleResource($this->sale),
            'date' => $this->created_at->getTimestamp(),
        ];
    }
}
