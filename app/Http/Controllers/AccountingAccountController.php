<?php

namespace App\Http\Controllers;

use App\Models\AccountingAccount;
use Illuminate\Http\Request;

class AccountingAccountController extends Controller
{
    public function getAccount(int $code)
    {
        return AccountingAccount::where("code", $code)->first();
    }
}
