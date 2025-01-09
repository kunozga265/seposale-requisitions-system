<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    protected $fillable = [
        'serial',
        'name',
        'phone_number',
        'email',
        'address',
        "organisation",
        "alias"
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];
}
