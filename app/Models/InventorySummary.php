<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySummary extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function sales()
    {
        return $this->hasMany(SiteSale::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function metrics()
    {
        $sum = 0;
        $profit = 0;
        $pending_payments = 0;
        foreach ($this->sales as $sale) {
            $sum += $sale->total;
            $profit += $sale->profit();
            $pending_payments += $sale->pendingPayments();
        }
        return [
            "total" => $sum,
            "profit" => $profit,
            "pending_payments" => $pending_payments,
        ];
    }

    protected $fillable = [
        "code",
        "serial",
        "opening_stock",
        "closing_stock",
        "comments",
        "user_id",
        "site_id",
        "date",
    ];
}
