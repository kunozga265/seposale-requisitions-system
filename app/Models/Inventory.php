<?php

namespace App\Models;

use App\Http\Resources\CollectionResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public function summaries()
    {
        return $this->hasMany(SiteSaleSummary::class);
    }
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    protected $fillable = [
        "name",
        "units",
        "cost",
        "site_id",
        "available_stock",
        "uncollected_stock",
        "threshold",
    ];
}
