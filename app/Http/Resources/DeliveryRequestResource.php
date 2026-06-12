<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryRequestResource extends JsonResource
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
            'date' => $this->created_at->getTimestamp(),
            'serial' => $this->serial,
            'status' => intval($this->status),
            'amount' => floatval($this->amount),
            'quantity' => floatval($this->quantity),
            'trips' => intval($this->trips),
            'paid' => boolval($this->paid),
            'active' => boolval($this->active),
            // 'sale' => intval($this->sale_id),
            // 'sale' => new SaleResource($this->sale),
            // 'summary' => new SummaryResource($this->summary),
            // 'delivery' => new DeliveryResource($this->delivery),
            'transportOption' => new TransportOptionResource($this->transportOption),
            // 'receipts' => ReceiptResource::collection($this->receipts),
        ];
    }
}
