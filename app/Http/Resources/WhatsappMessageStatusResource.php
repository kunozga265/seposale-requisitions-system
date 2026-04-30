<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WhatsappMessageStatusResource extends JsonResource
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
            "id" => intval($this->id),
            "status" => ucfirst($this->status),
            "payload" => json_decode($this->payload),
            "date" => $this->created_at->getTimestamp(),
        ];
    }
}
