<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    public function accountsGroups()
    {
        return $this->hasMany(AccountsGroup::class, 'account_type_id');
    }

    protected $fillable = [
        'name',
        'slug',
        'statement',
    ];
}
