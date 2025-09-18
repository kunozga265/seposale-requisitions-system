<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
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
            "id"                            =>  intval($this->id),
            "title"                         =>  $this->title,
            "description"                   =>  $this->description,
            "date"                          =>  intval($this->date),
            "slug"                          =>  $this->slug,
            "body"                          =>  $this->body,
            "fields"                        =>  json_decode($this->fields),
            "applications"                        =>  $this->applications->count(),

        ];
    }
}
