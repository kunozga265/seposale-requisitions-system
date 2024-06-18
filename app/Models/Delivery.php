<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    public function deliveredBy()
    {
        return $this->belongsTo(User::class,"delivered_by");
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, "initiated_by");
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    protected $fillable = [
        "status",
        "photo",
        "date_delivered",
        "date_initiated",
        "initiated_by",
        "delivered_by",
        "sale_id",
    ];
}
