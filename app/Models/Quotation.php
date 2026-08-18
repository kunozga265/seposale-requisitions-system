<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function __get($name)
    {
        if ($name === 'notes') {

            $meta =  json_decode($this->meta, true);
            if ($meta["notes"] != null && trim($meta["notes"]) != "") {
                return $meta["notes"];
            } else {
                return null;
            }
        }

        // It's important to call the parent __get() method
        // to allow other properties to be accessed normally.
        return parent::__get($name);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
    }

    protected $fillable = [
        "code",
        "serial",
        "client_id",
        "location",
        "recipient_name",
        "recipient_profession",
        "recipient_phone_number",
        "information",
        "total",
        "quotes",
        "user_id",
        "sale_id",
        "whatsapp",
        "vat",
        'vat_option',
        "meta",
        "client_generated",
        "confirmed",
        "branch_id",
    ];
}
