<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use App\Http\Controllers\SiteSaleSummaryController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    public function inventories()
    {
     return $this->hasMany(Inventory::class);
    }

    public function summaries()
    {
     return $this->hasMany(InventorySummary::class);
    }

    public function sales()
    {
     return $this->hasMany(SiteSale::class);
    }

    public function collections()
    {
     return $this->hasMany(Collection::class);
    }

    public function materials()
    {
     return $this->hasMany(Material::class);
    }

    public function productions(){
        return $this->hasMany(Production::class);
    }

    public function pendingCollections()
    {
        $summaries = SiteSaleSummary::latest()->get();

        $filtered = [];

        foreach ($summaries as $summary){
            if($summary->getCollectionStatus() < 2 && $summary->sale->site->id == $this->id){
                $filtered [] = [
                    "id" => $summary->id,
                    "inventory" => $summary->inventory,
                    'amount' => floatval($summary->amount),
                    'balance' => floatval($summary->balance),
                    'paymentStatus' => $summary->getPaymentStatus(),
                    'collected' => floatval($summary->collected),
                    'collectionStatus' => $summary->getCollectionStatus(),
                    'quantity' => floatval($summary->quantity),
                    "collections" =>(new SiteSaleSummaryController())->getCollections($summary->collections),
                    "site" => $summary->site,
                    "trashed" => $summary->deleted_at != null,
                    "sale" => [
                        "id" => $summary->sale->id,
                        "code" => (new AppController())->getZeroedNumber($summary->sale->code),
                        'client' => $summary->sale->client,
                    ],
                ];
            }
        }

        return $filtered;
    }

    protected $fillable = [
        "name",
        "code",
        "location"
    ];
}
