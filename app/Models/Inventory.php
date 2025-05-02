<?php

namespace App\Models;

use App\Http\Resources\CollectionResource;
use Carbon\Carbon;
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
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function damages()
    {
        return $this->hasMany(Damage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function stock(){
        $count = 0;
        foreach ($this->batches as $batch) {
            if($this->producible == 1){
                $now = Carbon::now();
                if($batch->ready_date <= $now->getTimestamp()){
                    $count+=$batch->balance;
                }
            }else{
                $count+=$batch->balance;
            }
        }
        return $count;
    }

    protected $fillable = [
        "name",
        "units",
        "cost",
        "site_id",
        "available_stock",
        "uncollected_stock",
        "threshold",
        "producible",
        "product_id",
    ];
}
