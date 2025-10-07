<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use App\Http\Controllers\SiteSaleSummaryController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function summaries()
    {
        return $this->hasMany(InventorySummary::class);
    }

    public function sales()
    {
        return $this->hasMany(SiteSale::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function accounts()
    {
        return $this->belongsToMany(AccountingAccount::class, "site_and_accounting_account", "site_id", "accounting_account_id");
    }

    public function walletAccount()
    {
        return $this->accounts()->where("special_type", "WALLET")->first();
    }

    public function directCostsAccount()
    {
        return $this->accounts()->where("special_type", "COGS-DIRECT-OSS")->first();
    }

    public function pendingCollections()
    {
        $summaries = SiteSaleSummary::latest()->get();

        $filtered = [];

        foreach ($summaries as $summary) {
            if ($summary->getCollectionStatus() < 2 && $summary->sale->site->id == $this->id) {
                $filtered[] = [
                    "id" => intval($summary->id),
                    "inventory" => $summary->inventory,
                    "inventoryStock" => floatval($summary->inventory->stock()),
                    'amount' => floatval($summary->amount),
                    'balance' => floatval($summary->balance),
                    'paymentStatus' => intval($summary->getPaymentStatus()),
                    'collected' => floatval($summary->collected),
                    'collectionStatus' => $summary->getCollectionStatus(),
                    'quantity' => floatval($summary->quantity),
                    "collections" => (new SiteSaleSummaryController())->getCollections($summary->collections),
                    "site" => $summary->site,
                    "trashed" => $summary->deleted_at != null,
                    "sale" => [
                        "id" => intval($summary->sale->id),
                        "code" => (new AppController())->getZeroedNumber($summary->sale->code),
                        'client' => $summary->sale->client,
                          'date' => intval($summary->sale->date),
                    ],
                    "status" => intval($summary->status),
                    "delivery" => $summary->delivery != null ? [
                        "id" => intval($summary->delivery->id),
                        "status" => intval($summary->delivery->status),
                    ] : null,
                    "overdue" => $summary->delivery != null ? $summary->delivery->overdue() : false,
                    'profit' => floatval($summary->profit()),
                    "unitCost" => floatval($summary->cost()),
                    'pendingPayments' => floatval($summary->paidBalance() < 0 ? abs($summary->paidBalance()) : 0),
                ];
            }
        }

        return $filtered;
    }

    protected $fillable = [
        "name",
        "code",
        "location"
    ];
}
