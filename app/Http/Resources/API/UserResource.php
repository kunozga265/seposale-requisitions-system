<?php

namespace App\Http\Resources\API;

use App\Http\Resources\PositionResource;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'              =>  $this->id,
            'firstName'       =>  $this->firstName,
            'middleName'      =>  $this->middleName,
            'lastName'        =>  $this->lastName,
            'fullName'        =>  $this->firstName." ".$this->lastName,
            'email'           =>  $this->email,
            'position'        =>  new PositionResource($this->position),
            'roles'           =>  RoleResource::collection($this->roles),
            'referrals'       =>  $this->referrals->count(),
        ];
    }
}
