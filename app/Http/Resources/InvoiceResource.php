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
            'code' => (new AppController())->getZeroedNumber($this->code),
            'name' => $this->name,
            'phoneNumber' => $this->phone_number,
            'email' => $this->email,
            'address' => $this->address,
            'location' => $this->location,
            'information' => json_decode($this->information),
            'total' => $this->total,
            'status' => $this->status,
            'requestedBy' => new UserResource($this->user),
            'receipts' => json_decode($this->receipts),
            'date' => $this->created_at->getTimestamp(),
        ];
    }
}
