<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Summary::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function pops()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    public function payables()
    {
        return $this->hasMany(Payable::class);
    }

    public function expense()
    {
        return $this->hasOne(Expense::class);
    }


    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }
    public function notes()
    {
        return $this->hasOne(Quotation::class);
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code_alt);
    }

    public function profit()
    {
        $sum = 0;
        foreach ($this->products as $summary) {
            $sum += $summary->profit();
        }
        return $sum;
    }

    public function vatBalance()
    {
        return floatval($this->vat) - floatval($this->vat_paid);
    }

    public function attachedReceipts()
    {
        return $this->belongsToMany(Receipt::class, 'receipt_sale', 'sale_id', 'receipt_id');
    }

    public function agents()
    {
        return $this->hasMany(SaleAgent::class);
    }

    /**
     * Copy agent commissions from the client's earliest agent-bearing sale onto $sale,
     * if $sale falls within 3 months of that earlier sale's date.
     */
    public static function inheritAgentsFor(Sale $sale): void
    {
        $firstSaleWithAgents = static::where('client_id', $sale->client_id)
            ->where('id', '!=', $sale->id)
            ->whereHas('agents')
            ->orderBy('date', 'asc')
            ->first();

        if (!$firstSaleWithAgents) {
            return;
        }

        $windowEnd = Carbon::createFromTimestamp($firstSaleWithAgents->date)->addMonths(3);
        $saleDate = Carbon::createFromTimestamp($sale->date);

        if ($saleDate->greaterThan($windowEnd)) {
            return;
        }

        foreach ($firstSaleWithAgents->agents as $agent) {
            SaleAgent::create([
                'sale_id' => $sale->id,
                'client_id' => $agent->client_id,
                'percentage' => $agent->percentage,
            ]);
        }
    }

    public function deliveries()
    {
        return $this->hasManyThrough(
            Delivery::class,
            Summary::class,
            'sale_id',
            'summary_id',
            'id',
            'id'
        );
    }

    // public function deliveryNotes()
    // {
    //     return DeliveryNote::whereHas('delivery', function ($q) {
    //         $q->whereIn('id', $this->deliveries()->pluck('id'));
    //     });
    // }

    public function deliveryNotes()
    {
        return DeliveryNote::query()
            ->whereHas('delivery.summary', function ($q) {
                $q->where('sale_id', $this->id);
            });
    }

    public function deliveryRequests()
    {
        return $this->hasMany(DeliveryRequest::class);
    }

    /**
     * Get all collections related to this sale
     */
    public function collections()
    {
        return \App\Models\Collection::whereIn(
            'site_sale_summary_id',
            $this->products()
                ->whereNotNull('site_sale_summary_id')
                ->pluck('site_sale_summary_id')
        );
    }


    protected $fillable = [
        "code",
        "serial",
        "code_alt",
        "status",
        "client_id",
        "total",
        "balance",
        "date",
        "editable",
        "comments",
        "location",
        "recipient_name",
        "recipient_profession",
        "recipient_phone_number",
        "user_id",
        "whatsapp",
        "local_purchase_order",

        'zone_id',
        'client_generated',
        'confirmed',
        'confirmed_date',
        'location_id',
        'meta',
        'vat',
        'vat_paid',
        'vat_option',
    ];
}
