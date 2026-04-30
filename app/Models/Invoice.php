<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
    }

    protected $fillable = [
        "code",
        "serial",
        "client_id",
        "sale_id",
        "revision",
        "whatsapp",
    ];
}
