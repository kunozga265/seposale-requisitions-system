<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountsGroup extends Model
{
    use HasFactory;

    public function type()
    {
       return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function accounts()
    {
        return $this->hasMany(AccountingAccount::class, 'accounts_group_id');
    }

   protected $fillable = [
        'name',
        'code',
        'account_type_id',
    ];
}
