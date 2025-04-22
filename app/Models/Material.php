<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    public function type() : BelongsTo {
        return $this->belongsTo(MaterialsType::class, "materials_type_id", "id");
    }

    public function batches() : HasMany {
        return $this->hasMany(Batch::class);
    }

   protected $fillable = [
       "name",
       "units",
       "site_id",
       "quantity",
       "threshold",
       "materials_type_id",
   ];
}
