<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            "id"                    => $this->id,
            "firstName"             => $this->first_name,
            "lastName"              => $this->last_name,
            "dateOfBirth"           => $this->date_of_birth,
            "gender"                => $this->gender,
            "phoneNumber"           => $this->phone_number,
            "email"           => $this->email,
            "qualifications"        => $this->qualifications,
            "fields"                => json_decode($this->fields),
            "vacancy"        => $this->vacancy,
        ];
    }
}
