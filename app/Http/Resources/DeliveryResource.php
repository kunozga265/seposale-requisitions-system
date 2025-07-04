<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => intval($this->id),
            'serial' => $this->serial,
            'code' => (new AppController())->getZeroedNumber($this->code),
            "status" => intval($this->status),
            "photo" => $this->photo,
            "trackingNumber" => $this->tracking_number,
            "notes" => json_decode($this->notes),
            "client" => $this->summary->sale->client,
            "location" => $this->summary->sale->location,
            "expense" => new ExpenseResource($this->expense),
            "quantityDelivered" => floatval($this->quantity_delivered),
            "quantityBalance" => floatval($this->summary->quantity - $this->quantity_delivered),
            "summary" => new SummaryResource($this->summary),
            "date" => intval($this->due_date),
            "due" => $this->status == 1 ? Carbon::createFromTimestamp($this->due_date)->diffForHumans() : null,
            "overdue" => $this->status == 1 ? $this->overdue() : false,
            "logs" => SystemLogResource::collection($this->logs),
            'whatsapp' => $this->whatsapp != null ? intval($this->whatsapp) : false ,
            "costBalance" => $this->availableCostBalance(),
        ];
    }
}
