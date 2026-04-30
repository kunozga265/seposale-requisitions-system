<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

      public function siteSales()
    {
        return $this->belongsToMany(SiteSale::class,'site_sales_batches','batch_id','site_sale_id');
    }

    protected $fillable = [
        "user_id",
        "date",
        "price",
        "quantity",
        "accounting_balance",
        "balance",
        "comments",
        "photo",
        "inventory_id",
        "material_id",
        "production_id",
        "ready_date",
    ];
}
