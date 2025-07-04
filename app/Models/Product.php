<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function cogsAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'cogs_account_id', 'id');
    }

    public function inventoryAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'inventory_account_id', 'id');
    }

    public function revenueAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'revenue_account_id', 'id');
    }

    protected $fillable = [
        "name",
        "cogs_account_id",
        "inventory_account_id",
        "revenue_account_id",
    ];
}
