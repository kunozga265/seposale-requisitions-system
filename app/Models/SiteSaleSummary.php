<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSaleSummary extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function description()
    {
        return $this->inventory->name;
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function sale()
    {
        return $this->belongsTo(SiteSale::class, "site_sale_id", "id");
    }

    public function collections()
    {
        return $this->hasMany(Collection::class,);
    }

    public function receiptSummaries()
    {
        return $this->hasMany(ReceiptSummary::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

       public function summary()
    {
        return $this->hasOne(Summary::class,  "site_sale_summary_id","id");
    }

    public function deliveryExists()
    {
        if ($this->delivery == null) {
            return false;
        } else if ($this->delivery->quantity_delivered == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function formattedUnits($quantity)
    {
        $plural = $quantity != 1 ? "s" : "";
        $formatted = number_format($quantity, 2);
        return "$formatted {$this->inventory->units}$plural";
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

    public function getFullPaymentStatus()
    {
        if (isset($this->balance)) {
            if ($this->balance == $this->amount) {
                return "Unpaid";
            } elseif ($this->balance > 0 && $this->balance < $this->amount) {
                return "Partially Paid";
            } elseif ($this->balance == 0) {
                return "Paid";
            }
        }
        return 3;
    }
    public function getCollectionStatus()
    {

        if ($this->collected == $this->quantity) {
            return 2;
        } elseif ($this->collected > 0 && $this->collected < $this->quantity) {
            return 1;
        } else {
            return 0;
        }
    }

    public function cost()
    {
        return $this->amount / $this->quantity;
    }

    public function paidBalance()
    {
        $total = 0;
        foreach ($this->collections as $collection) {
            $total += $collection->quantity * $this->cost();
        }
        // dump("Amount ".$this->amount);
        // dump("Balance ".$this->balance);
        // dump("Unit Cost ".$this->cost());
        // dump("Total ".$total);

        return $this->amount - $this->balance - $total;
    }

    protected $fillable = [
        "inventory_id",
        "site_sale_id",
        "quantity",
        "amount",
        "balance",
        "collected",
        "cost",
        "status",
    ];
}
