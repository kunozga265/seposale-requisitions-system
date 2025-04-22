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

    protected $fillable = [
        "code",
        'date',
        "opening_stock",
        "closing_stock",
        "user_id",
        'site_id',
    ];
}
