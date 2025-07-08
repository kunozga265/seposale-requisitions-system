<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use App\Http\Controllers\SiteSaleSummaryController;
use App\Http\Resources\CollectionResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public function summaries()
    {
        return $this->hasMany(SiteSaleSummary::class);
    }
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function damages()
    {
        return $this->hasMany(Damage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

     public function pendingCollections()
    {


        $filtered = [];
        foreach ($this->summaries as $summary){
            if($summary->getCollectionStatus() < 2){
                $filtered [] = [
                    "id" => $summary->id,
                    "inventory" => $summary->inventory,
                    "inventoryStock" => $summary->inventory->stock(),
                    'amount' => floatval($summary->amount),
                    'balance' => floatval($summary->balance),
                    'paymentStatus' => $summary->getPaymentStatus(),
                    'collected' => floatval($summary->collected),
                    'collectionStatus' => $summary->getCollectionStatus(),
                    'quantity' => floatval($summary->quantity),
                    "collections" =>(new SiteSaleSummaryController())->getCollections($summary->collections),
                    "site" => $summary->site,
                    "trashed" => $summary->deleted_at != null,
                    "sale" => [
                        "id" => $summary->sale->id,
                        "date" => $summary->sale->date,
                        "code" => (new AppController())->getZeroedNumber($summary->sale->code),
                        'client' => $summary->sale->client,
                    ],
                ];
            }
        }

          usort($filtered, function ($a, $b) {
                if ($a['sale']['date'] < $b['sale']['date']) {
                    return -1;
                } elseif ($a['sale']['date'] > $b['sale']['date']) {
                    return 1;
                }
                return 0;
            });

        return $filtered;
    }

    public function stock()
    {
        $count = 0;
        $batches = $this->batches()->where("accounting_balance",">",0)->get();
        foreach ($batches as $batch) {
            if ($this->producible == 1) {
                $now = Carbon::now();
                if ($batch->ready_date <= $now->getTimestamp()) {
                    $count += $batch->accounting_balance;
                }
            } else {
                $count += $batch->accounting_balance;
            }
        }
        return $count;
    }

    public function value()
    {
         $value = 0;
        if($this->inventoryAccount != null){
            $value = $this->inventoryAccount->balance;
            error_log("Name: {$this->name}");
            error_log("Value: {$value}");
            foreach($this->inventoryAccount->inventories as $inventory){
                  error_log("Inventory Loop: {$inventory->name}");
                $batches = $inventory->batches()->where("accounting_balance",">",0)->get();
                foreach($batches as $batch){
                    error_log("Batch Loop: {$batch->date}");
                    
                    error_log($batch->price * $batch->accounting_balance);
                    $value -= $batch->price * $batch->accounting_balance;
                }
            }
        }
        return $value;
    }


    public function cogsAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'cogs_account_id', 'id');
    }

    public function inventoryAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'inventory_account_id', 'id');
    }

    public function revenueAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'revenue_account_id', 'id');
    }

    public function checkAccounts()
    {
        if ($this->cogsAccount == null) {
            return false;
        } else if ($this->inventoryAccount == null) {
            return false;
        } else if ($this->revenueAccount == null) {
            return false;
        } else {
            return true;
        }
    }

    public function inventoryValue() {}

    public function formattedUnits($quantity)
    {
        $plural = $quantity != 1 ? "s" : "";
        $formatted = number_format($quantity, 2);
        return "$formatted {$this->units}$plural";
    }


    protected $fillable = [
        "name",
        "units",
        "cost",
        "site_id",
        "available_stock",
        "uncollected_stock",
        "threshold",
        "producible",
        "product_id",
        "cogs_account_id",
        "inventory_account_id",
        "revenue_account_id",
    ];
}
