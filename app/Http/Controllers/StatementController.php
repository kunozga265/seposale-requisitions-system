<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountingAccountResource;
use App\Http\Resources\AccountingRecordResource;
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

        return Inertia::render('Statements/Index', [
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

            return Inertia::render('Statements/Show', [
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

    public function account($serial, $code)
    {
        $statement = Statement::where("serial", $serial)->first();
        if (!$statement) {
            abort(404, 'Statement not found');
        }
        // Find the accounting account by code
        $account = AccountingAccount::where('code', $code)->firstOrFail();

        // Load the related records
        $account->load('records');

        // If the account is not found, return a 404 response
        if (!$account) {
            abort(404, 'Accounting account not found');
        }

        $end_date = $statement->end_date ?? Carbon::now()->getTimestamp();

        $records = $account->records()
            ->where("date", ">=", $statement->start_date)
            ->where("date", "<=", $end_date)
            ->orderBy("date","desc")
            ->get();

        // Return the accounting account details view
        return Inertia::render('Statements/Account', [
            'statement' => $statement,
            'account' => $account,
            'records' => AccountingRecordResource::collection($records),
        ]);
    }
}
