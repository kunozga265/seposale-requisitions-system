<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\CreditVoucher;
use App\Http\Resources\CreditVoucherResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Support\Carbon;

class CreditVoucherController extends Controller
{
    public function index(Request $request)
    {
        $credit_vouchers = CreditVoucher::orderBy("code", "desc")->paginate((new AppController())->paginate);


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(CreditVoucherResource::collection($credit_vouchers));
        else {
            //Web Response
            return Inertia::render('CreditVouchers/Index', [
                'creditVouchers' => CreditVoucherResource::collection($credit_vouchers),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $credit_voucher = CreditVoucher::find($id);

        if (is_object($credit_voucher)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new CreditVoucherResource($credit_voucher));
            } else {
                //Web Response
                return Inertia::render('CreditVouchers/Show', [
                    'creditVoucher' => new CreditVoucherResource($credit_voucher),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Credit voucher not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Credit voucher not found');
            }
        }
    }

    public function getCodeNumber()
    {
        $last = CreditVoucher::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }

    public function print(Request $request, $id)
    {
        //find out if the request is valid
        $credit_voucher = CreditVoucher::find($id);

        if (is_object($credit_voucher)) {

            $filename = "CREDIT VOUCHER #" . (new AppController())->getZeroedNumber($credit_voucher->code) . " - " . $credit_voucher->getName() . "-" . date('Ymd', $credit_voucher->date);

            $now_d = Carbon::createFromTimestamp($credit_voucher->date, 'Africa/Lusaka')->format('F j, Y');
            $now_t = Carbon::createFromTimestamp($credit_voucher->date, 'Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($credit_voucher->amount)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            $total_in_words = str_replace(" of ", " ", $total_in_words);

            $pdf = PDF::loadView('credit-voucher', [
                'code'              => (new AppController())->getZeroedNumber($credit_voucher->code),
                'date'              => $now_d,
                'time'              => $now_t,
                'credit_voucher'    => $credit_voucher,
                'total_in_words'    => $total_in_words
            ]);
            return $pdf->download("$filename.pdf");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Credit voucher not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Credit voucher not found');
            }
        }
    }
}
