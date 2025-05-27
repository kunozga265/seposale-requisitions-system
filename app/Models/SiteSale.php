<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSale extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function products()
    {
        return $this->hasMany(SiteSaleSummary::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function inventorySummary()
    {
        return $this->belongsTo(InventorySummary::class);
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

     public function batches()
    {
        return $this->belongsToMany(Batch::class,'site_sales_batches','site_sale_id','batch_id');
    }

    protected $fillable = [
        "code",
        "serial",
        "status",
        "client_id",
        "total",
        "balance",
        "date",
        "editable",
        "user_id",
        "site_id",
        "inventory_summary_id",
        "whatsapp",
        "payment_method_id",
        "reference",
    ];
}
