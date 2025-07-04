<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Material extends Model
{
    use HasFactory;

    public function type(): BelongsTo
    {
        return $this->belongsTo(MaterialsType::class, "materials_type_id", "id");
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function cogsAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'cogs_account_id', 'id');
    }

    public function inventoryAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'inventory_account_id', 'id');
    }

    public function batchesValue()
    {
        $total = 0;
        $batches = $this->batches()->where("accounting_balance",">",0)->get();
        foreach($batches as $batch){
            $total += $batch->price * $batch->accounting_balance;
            Log::info("Batch Value");
            Log::info($total);
        }

        return $total;
    }

    public function formattedUnits($quantity)
    {
        $plural = $quantity != 1 ? "s" : "";
        $formatted = number_format($quantity, 2);
        return "$formatted {$this->units}$plural";
    }

    protected $fillable = [
        "name",
        "units",
        "site_id",
        "quantity",
        "threshold",
        "materials_type_id",
        "inventory_account_id",
        "cogs_account_id",
    ];
}
