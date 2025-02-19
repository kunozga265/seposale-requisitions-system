<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReceiptResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\SiteSaleResource;
use App\Models\Receipt;
use App\Models\Sale;
use App\Models\SiteSale;
use App\Models\SiteSaleSummary;
use App\Models\Summary;
use App\Models\SystemLog;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Rmunate\Utilities\SpellNumber;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $receipts = Receipt::orderBy("date", "desc")->paginate(100);


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(ReceiptResource::collection($receipts));
        else {
            //Web Response
            return Inertia::render('Receipts/Index', [
                'receipts' => ReceiptResource::collection($receipts),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $receipt = Receipt::find($id);

        if (is_object($receipt)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new ReceiptResource($receipt));
            } else {
                //Web Response
                return Inertia::render('Receipts/Show', [
                    'receipt' => new ReceiptResource($receipt),
                    'sale' => $receipt->sale != null ? new SaleResource($receipt->sale) : null,
                    'siteSale' => $receipt->siteSale != null ? new SiteSaleResource($receipt->siteSale) : null,
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Receipt not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Receipt not found');
            }
        }
    }

    public function store(Request $request, $id)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        $sale = $request->type == "ORDINARY" ? Sale::find($id) : SiteSale::find($id);

        if (is_object($sale)) {

            $receipt = Cache::lock($user->id . ':receipt:store', 10)->get(function () use ($user, $request, $sale) {

                //Validate all the important attributes
                $request->validate([
                    'account_id' => ['required'],
                    'payment_method_id' => ['required'],
                    'information' => ['required'],
                    'type' => ['required'],
                ]);

                //Checking
                $total = 0;
                $filteredProducts = [];
                foreach ($request->information as $item) {
                    $amount = $item["amount"];
                    $total += $amount;

                    $summary = $request->type == "ORDINARY" ? Summary::findOrFail($item["id"]) : SiteSaleSummary::findOrFail($item["id"]);
                    if (isset($summary->balance)) {
                        $balance = $summary->balance - $amount;
                        if ($balance < 0) {
                            return Redirect::back()->with("error", "Payment is more than what is required");
                        }
                    }
                    if ($amount > 0) {
                        $filteredProducts[] = $item;
                    }
                }

                $new_balance = $sale->balance - $total;
                if ($new_balance < 0) {
                    return Redirect::back()->with("error", "Payment is more than what is required");
                } else if ($total <= 0) {
                    return Redirect::back()->with("error", "Receipt amount is zero");
                }

                //Updating data
                foreach ($filteredProducts as $item) {
                    $amount = $item["amount"];
                    $summary = $request->type == "ORDINARY" ? Summary::findOrFail($item["id"]) : SiteSaleSummary::findOrFail($item["id"]);
                    if (isset($summary->balance)) {
                        $balance = $summary->balance - $amount;
                        $summary->update([
                            "balance" => $balance
                        ]);
                    }
                }

                $receipt = Receipt::create([
                    'code' => $this->getCodeReceiptNumber(),
                    'serial' =>  (new AppController())->generateUniqueCode("RECEIPT"),
                    'client_id' => $sale->client->id,
                    "sale_id" =>  $request->type == "ORDINARY" ? $sale->id : null,
                    "site_sale_id" =>  $request->type == "SITE" ? $sale->id : null,
                    'account_id' => $request->account_id,
                    'payment_method_id' => $request->payment_method_id,
                    'amount' => $total,
                    'reference' => strtoupper($request->reference),
                    'information' => json_encode($filteredProducts),
                    'user_id' => $user->id,
                    'date' => isset($request->date) ? $request->date : \Carbon\Carbon::now()->getTimestamp(),
                ]);

                $sale->update([
                    "balance" => $new_balance,
                    "editable" => false,
                    "status" => $new_balance == 0 ? 2 : 1
                ]);

                $balance =  $receipt->account->balance + $receipt->amount;
                $receipt->account->update([
                    "balance" => $balance
                ]);

                Transaction::create([
                    "date" => $receipt->date,
                    "reference" => strtoupper($receipt->reference),
                    "description" => $receipt->listOfProducts(),
                    "from_to" => $receipt->client->name,
                    "expense_id" => null,
                    "receipt_id" => $receipt->id,
                    "account_id" => $receipt->account->id,
                    "total" => $receipt->amount,
                    "balance" => $balance,
                    "type" => "CREDIT",
                ]);

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Receipt #{$receipt->code} created for {$sale->client->name} under Sale #{$sale->code_alt}. Total amount received is {$receipt
        ->amount}",
                    "sale_id" =>  $request->type == "ORDINARY" ? $sale->id : null,
                    "site_sale_id" =>  $request->type == "SITE" ? $sale->id : null,

                ]);

                return $receipt;
            });


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json($receipt, 201);
            else {
                //Web Response
                return Redirect::back()->with('success', 'Receipt generated!');
            }
        } else {
            return Redirect::back()->with('error', 'Sale not found');
        }


    }

    public function print(Request $request, $id)
    {
        //find out if the request is valid
        $receipt = Receipt::find($id);

        if (is_object($receipt)) {

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $filename = "RECEIPT#" . (new AppController())->getZeroedNumber($receipt->code) . " - " . $receipt->client->name . "-" . date('Ymd', $receipt->date);

            $now_d = Carbon::createFromTimestamp($receipt->date, 'Africa/Lusaka')->format('F j, Y');
            $now_t = Carbon::createFromTimestamp($receipt->date, 'Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($receipt->amount)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            $total_in_words = str_replace(" of ", " ", $total_in_words);

            $pdf = PDF::loadView('receipt', [
                'code' => (new AppController())->getZeroedNumber($receipt->code),
                'date' => $now_d,
                'time' => $now_t,
                'receipt' => new ReceiptResource($receipt),
                'total_in_words' => $total_in_words
            ]);
            return $pdf->download("$filename.pdf");

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Invoice not found"], 404);
            } else {


                //Web Response
                return Redirect::route('dashboard')->with('error', 'Invoice not found');
            }
        }


    }


    private function getCodeReceiptNumber()
    {
        $last = Receipt::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }
}
