<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    use HasFactory;

    public function material(){
        return $this->belongsTo(Material::class);
    }

    public function batch(){
        return $this->belongsTo(Batch::class);
    }

    protected $fillable = [
        "date",
        "quantity",
        "cost",
        "material_id",
        "production_id",
        "batch_id",
    ];
}
