<?php

namespace App\Http\Resources\API;

use App\Http\Resources\InventoryResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::find(Auth::id());

        $pending = [];
        if ($user->hasRole('management') || $user->hasRole('oss')) {
            $pending = $this->pendingCollections();
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "code" => $this->code,
            "location" => $this->location,
            "inventories" => InventoryResource::collection($this->inventories),
            "pendingCollections" => $pending,
            // "accounts" => $this->accounts,
        ];
    }
}
