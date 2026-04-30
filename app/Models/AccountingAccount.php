<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingAccount extends Model
{
    use HasFactory;

    public function accountsGroup()
    {
        return $this->belongsTo(AccountsGroup::class, 'accounts_group_id');
    }
 
    public function records()
    {
        return $this->hasMany(AccountingRecord::class,);
    }
 
    public function inventories()
    {
        return $this->hasMany(Inventory::class,"inventory_account_id","id");
    }

    protected $fillable = [
        'name',
        'code',
        'type',
        'special_type',
        'description',
        'is_active',
        'accounts_group_id',
        'balance',
    ];
}
