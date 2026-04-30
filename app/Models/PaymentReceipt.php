<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function siteSale()
    {
        return $this->belongsTo(SiteSale::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getType()
    {
        if ($this->file != null) {
            $file = explode('.', $this->file);
            switch ($file[1]) {
                case 'pdf':
                    return $file[1];
                case 'jpeg':
                case 'jpg':
                case 'png':
                    return 'image';
                default:
                    return 'other';
            };
        } else if ($this->description != null) {
            return 'text';
        } else {
            return "none";
        }
    }

    protected $fillable = [
        'date',
        'amount',
        'file',
        'description',
        'active',
        'sale_id',
        'site_sale_id',
        'user_id',
        'payment_method_id',
    ];
}
