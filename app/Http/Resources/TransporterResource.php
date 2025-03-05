<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransporterResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "phoneNumber" => $this->phone_number != null ? "+{$this->phone_number}" : "",
            "phoneNumberOther" => $this->phone_number_other != null ? "+{$this->phone_number_other}" : "",
            "email" =>  $this->email,
            "location" => $this->location,
            "registrationNumber" => $this->registration_number,
            "type" =>  $this->type,
            "make" => $this->make,
        ];
    }
}
