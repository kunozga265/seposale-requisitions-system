<?php

namespace App\Http\Resources;

use App\Models\Receipt;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $filteredReceipts = [];
        $receipts = Receipt::where("site_sale_id","!=",null)->orderBy("date","asc")->get();
        foreach ($receipts as $receipt){
            if ($receipt->siteSale->site->id == $this->id){
                $filteredReceipts[] = $receipt;
            }
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "code" => $this->code,
            "location" => $this->location,
            "inventories" => InventoryResource::collection($this->inventories),
            "summaries" => InventorySummaryResource::collection($this->summaries()->orderBy("date","desc")->get()),
//            "collections" => CollectionResource::collection($this->collections()->orderBy("date","desc")->get()),
            "pendingCollections" => $this->pendingCollections(),
            "receipts" => ReceiptResource::collection($filteredReceipts)

        ];
    }
}
