<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleAgent extends Model
{
    protected $table = 'sale_agents';

    protected $fillable = [
        'sale_id',
        'client_id',
        'percentage',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
