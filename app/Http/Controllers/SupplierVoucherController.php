<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\SupplierVoucher;
use App\Http\Resources\SupplierVoucherResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Rmunate\Utilities\SpellNumber;
use Illuminate\Support\Carbon;

class SupplierVoucherController extends Controller
{
     public function index(Request $request)
    {
        $supplier_vouchers = SupplierVoucher::orderBy("code", "desc")->paginate((new AppController())->paginate);


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(SupplierVoucherResource::collection($supplier_vouchers));
        else {
            //Web Response
            return Inertia::render('SupplierVouchers/Index', [
                'supplierVouchers' => SupplierVoucherResource::collection($supplier_vouchers),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $supplier_voucher = SupplierVoucher::find($id);

        if (is_object($supplier_voucher)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new SupplierVoucherResource($supplier_voucher));
            } else {
                //Web Response
                return Inertia::render('SupplierVouchers/Show', [
                    'supplierVoucher' => new SupplierVoucherResource($supplier_voucher),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Supplier voucher not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Supplier voucher not found');
            }
        }
    }

    public function getCodeNumber()
    {
        $last = SupplierVoucher::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }

    public function print(Request $request, $id)
    {
        //find out if the request is valid
        $supplier_voucher = SupplierVoucher::find($id);

        if (is_object($supplier_voucher)) {

            $filename = "SUPPLIER VOUCHER #" . (new AppController())->getZeroedNumber($supplier_voucher->code) . " - " . $supplier_voucher->getName() . "-" . date('Ymd', $supplier_voucher->date);

            $now_d = Carbon::createFromTimestamp($supplier_voucher->date, 'Africa/Lusaka')->format('F j, Y');
            $now_t = Carbon::createFromTimestamp($supplier_voucher->date, 'Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($supplier_voucher->amount)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            $total_in_words = str_replace(" of ", " ", $total_in_words);

            $pdf = PDF::loadView('supplier-voucher', [
                'code'              => (new AppController())->getZeroedNumber($supplier_voucher->code),
                'date'              => $now_d,
                'time'              => $now_t,
                'supplier_voucher'    => $supplier_voucher,
                'total_in_words'    => $total_in_words
            ]);
            return $pdf->download("$filename.pdf");
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Supplier voucher not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Supplier voucher not found');
            }
        }
    }
}
