<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site(){
        return $this->belongsTo(Site::class);
    }

    public function usages()
    {
        return $this->hasMany(Usage::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function damages()
    {
        return $this->hasMany(Damage::class);
    }

    protected $fillable = [
        "code",
        'date',
        "opening_stock",
        "closing_stock",
        "expenses",
        "user_id",
        'site_id',
    ];
}
