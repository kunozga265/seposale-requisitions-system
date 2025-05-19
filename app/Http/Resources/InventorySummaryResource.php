<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use App\Models\InventorySummary;
use Illuminate\Http\Resources\Json\JsonResource;

class InventorySummaryResource extends JsonResource
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
            "date" => $this->date,
            "code" => (new AppController())->getZeroedNumber($this->code),
            "openingStock" => json_decode($this->opening_stock),
            "closingStock" => json_decode($this->closing_stock),
            "comments" => json_decode($this->comments),
            "user" => new UserResource($this->user),
            "totalSales" => $this->totalSales(),
            "collections" => $this->collections->count(),
            "active" => !InventorySummary::where("created_at", ">",$this->created_at)->exists()
        ];
    }
}
