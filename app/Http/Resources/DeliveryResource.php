<?php

namespace App\Http\Resources;

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
            "id" => $this->id,
            "status" => $this->status,
            "photo" => $this->photo,
            "dateInitiated" => $this->date_initiated,
            "dateDelivered" => $this->date_delivered,
            'deliveredBy' => new UserResource($this->deliveredBy),
            'initiatedBy' => new UserResource($this->initiatedBy),
            "sale" => $this->sale,
        ];
    }
}
