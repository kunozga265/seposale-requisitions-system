<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Summary extends Model

{
    use HasFactory;
    use SoftDeletes;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function receiptSummaries()
    {
        return $this->hasMany(ReceiptSummary::class);
    }

    public function fullName()
    {
        if ($this->variant != null) {
            return $this->product->name . " - " . $this->variant->description;
        } else {
            return $this->product->name;
        }
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, "product_variant_id", "id");
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function deliveryExists()
    {
        if ($this->delivery == null) {
            return false;
        } else if ($this->delivery->quantity_delivered == 0) {
            return false;
        }else{
            return true;
        }
    }

    public function siteSaleSummary()
    {
        return $this->hasOne(SiteSaleSummary::class, "id", "site_sale_summary_id");
    }

    public function getPaymentStatus()
    {
        if (isset($this->balance)) {
            if ($this->balance == $this->amount) {
                return 0;
            } elseif ($this->balance > 0 && $this->balance < $this->amount) {
                return 1;
            } elseif ($this->balance == 0) {
                return 2;
            }
        }
        return 3;
    }

    public function getCollectionStatus()
    {
        if($this->siteSaleSummary == null){
            return 0;
        }else{
            return $this->siteSaleSummary->getCollectionStatus();
        }
    }


    public function cost()
    {
        return $this->amount / $this->quantity;
    }

    public function formattedUnits($quantity)
    {
        $plural = $quantity != 1 ? "s" : "";
        $formatted = number_format($quantity, 2);
        return "$formatted {$this->units}$plural";
    }


    public function paidBalance()
    {
        $total = 0;
        $notes = [];
        if($this->delivery != null){
            $notes = json_decode($this->delivery->notes, true) ?? [];
        }
        foreach ($notes as $note) {
            $total += $note["total"] ?? 0;
        }
        return $this->amount - $this->balance - $total;
    }


    protected $fillable = [
        "product_id",
        "product_variant_id",
        "sale_id",
        "date",
        "amount",
        "balance",
        "quantity",
        "description",
        "units",
        "status",
        "site_sale_summary_id",
    ];
}
