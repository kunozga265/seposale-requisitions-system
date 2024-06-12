<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReceiptResource;
use App\Http\Resources\SaleResource;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Rmunate\Utilities\SpellNumber;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {
        $receipts = Receipt::orderBy("date","desc")->paginate(100);


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
        $receipt=Receipt::find($id);

        if(is_object($receipt)){
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new ReceiptResource($receipt));
            }else{
                //Web Response
                return Inertia::render('Receipts/Show',[
                    'receipt' => new ReceiptResource($receipt),
                    'sale' => new SaleResource($receipt->sale),
                ]);
            }
        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Receipt not found"], 404);
            }else{
                //Web Response
                return Redirect::route('dashboard')->with('error','Receipt not found');
            }
        }
    }

    public function print(Request $request,$id)
    {
        //find out if the request is valid
        $receipt=Receipt::find($id);

        if(is_object($receipt)){

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $filename="RECEIPT#".(new AppController())->getZeroedNumber($receipt->code)." - ".$receipt->client->name."-".date('Ymd',$receipt->date);

            $now_d=Carbon::createFromTimestamp($receipt->date,'Africa/Lusaka')->format('F j, Y');
            $now_t=Carbon::createFromTimestamp($receipt->date,'Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($receipt->amount)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            $total_in_words = str_replace(" of "," ",$total_in_words);

            $pdf = PDF::loadView('receipt', [
                'code'              => (new AppController())->getZeroedNumber($receipt->code),
                'date'              => $now_d,
                'time'              => $now_t,
                'receipt'           => new ReceiptResource($receipt),
                'total_in_words'    => $total_in_words
            ]);
            return $pdf->download("$filename.pdf");

        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Invoice not found"], 404);
            }else{


                //Web Response
                return Redirect::route('dashboard')->with('error','Invoice not found');
            }
        }
    }
}
