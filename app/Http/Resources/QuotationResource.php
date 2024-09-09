<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
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
            'id' => $this->id,
            'code' => (new AppController())->getZeroedNumber($this->code),
            'client' => new ClientResource($this->client),
            'location' => $this->location,
            'recipientName' => $this->recipient_name,
            'recipientProfession' => $this->recipient_profession,
            'recipientPhoneNumber' => $this->recipient_phone_number,
            'information' => json_decode($this->information),
            'total' => $this->total,
            'requestedBy' => new UserResource($this->user),
            'quotes' => json_decode($this->quotes),
            'date' => $this->created_at->getTimestamp(),
            'hasSale' => $this->sale_id != null,
        ];
    }


}
