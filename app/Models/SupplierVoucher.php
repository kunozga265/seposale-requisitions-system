<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AppController;

class SupplierVoucher extends Model
{
    use HasFactory;

    public function __get($name)
    {
        if ($name === 'contact') {
            if ($this->transporter != null) {
                return $this->transporter;
            } else {
                return $this->supplier;
            }
        } else  if ($name === 'details') {
            return $this->requestFormItem->details;
        } else  if ($name === 'type') {
            if ($this->transporter != null) {
                return "delivery";
            } else {
                return "supply";
            }
        } else  if ($name === 'quantity') {
            return $this->requestFormItem->quantity * ($this->amount / $this->requestFormItem->total_cost);
        } else  if ($name === 'readable_quantity') {
            if ($this->transporter != null) {
                $readable_quantity = $this->quantity . " Trip";
                $readable_quantity .= $this->quantity == 1 ? '' : 's';
            } else {
                $readable_quantity = $this->requestFormItem->inventory->formattedUnits($this->quantity);
            }
            return "$readable_quantity";
        } else  if ($name === 'unit_cost') {
            return $this->amount / $this->quantity;
        } else  if ($name === 'total_quantity') {
            return $this->requestFormItem->inventory->formattedUnits($this->requestFormItem->quantity);
        }


        // It's important to call the parent __get() method
        // to allow other properties to be accessed normally.
        return parent::__get($name);
    }

    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class, "request_id", "id");
    }
    public function requestFormItem()
    {
        return $this->belongsTo(RequestFormItem::class, "request_form_item", "id");
    }
    public function payoutRequestForm()
    {
        return $this->belongsTo(RequestForm::class, "payout_request_id", "id");
    }
    public function payoutRequestFormItem()
    {
        return $this->belongsTo(RequestFormItem::class, "payout_request_form_item", "id");
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payable()
    {
        return $this->belongsTo(Payable::class);
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function getName()
    {
        if ($this->transporter != null) {
            return $this->transporter->name;
        } else if ($this->supplier != null) {
            return $this->supplier->name;
        } else if ($this->requestForm != null) {
            return $this->requestForm->user->fullName();
        } else
            return "Other";
    }

    protected $fillable = [
        "serial",
        "code",
        "date",
        "amount",
        "balance",
        "payable_id",
        "transporter_id",
        "supplier_id",
        "site_id",
        "paid",
        "request_id",
        "request_form_item",
        "payout_request_id",
        "payout_request_form_item",
    ];
}
