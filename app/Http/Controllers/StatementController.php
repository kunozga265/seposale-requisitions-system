<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountingAccountResource;
use App\Http\Resources\StatementResource;
use App\Models\AccountingAccount;
use App\Models\AccountingRecord;
use App\Models\Statement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StatementController extends Controller
{
    public function index()
    {

        return Inertia::render('Reports/Statements', [
            'section' => 'overview',
            'balanceSheet' => $this->getStatement("balance-sheet"),
            'incomeStatement' => $this->getStatement("income-statement"),

        ]);
    }

    public function getStatement($type)
    {
        $statement = Statement::where("type", $type)
            ->where("active", true)
            ->first();

        if (!is_object($statement)) {
            $year = Carbon::now()->year . "/" . Carbon::now()->addYear()->year;
            $last = Statement::where("type", $type)
                ->where("active", false)
                ->orderBy("start_date", "desc")
                ->first();

            $statement = Statement::create([
                "serial" => (new AppController())->generateUniqueCode("STATEMENT"),
                "name"  => "$year",
                "active" => true,
                "start_date" => is_object($last) ? $last->end_date : 0,
                "type" => $type
            ]);
        }

        return $statement;
    }


    public function show(Request $request, $serial)
    {
        $statement = Statement::where("serial", $serial)->first();

        if (is_object($statement)) {

            return Inertia::render('Reports/Statement', [
                'statement' => new StatementResource($statement)
            ]);
            
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Statement not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Statement not found');
            }
        }
    }
}
