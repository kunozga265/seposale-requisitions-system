<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryResource;
use App\Http\Resources\PayableResource;
use App\Http\Resources\RequestFormResource;
use App\Models\Payable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayableController extends Controller
{
    public function index(Request $request)
    {
        $payables = Payable::where("paid",0)->orderBy("date","asc")->get();
        $suppliers = [];
        $transporters = [];

        foreach ($payables as $payable){
            if($payable->transporter != null){
                $transporters[]=$payable;
            }else if($payable->supplier != null){
                $suppliers[]=$payable;
            }
        }

//        $suppliers = $this->convertToArray($suppliers);
//        $transporters = $this->convertToArray($transporters);


        $groupedTransporters = array_reduce($transporters, function ($carry, $item) {
            $carry[$item['transporter_id']][] = $this->convertToArray(($item));
            return $carry;
        }, []);

        $groupedSuppliers = array_reduce($suppliers, function ($carry, $item) {
            $carry[$item['supplier_id']][] = $this->convertToArray(($item));
            return $carry;
        }, []);


//        dd((new AppController())->generateCompound(DeliveryResource::collection($deliveries)));

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(PayableResource::collection($payables));
        else {
            //Web Response
            return Inertia::render('Payables/Index', [
                "transporters" => $groupedTransporters,
                "suppliers" => $groupedSuppliers,

            ]);
        }
    }

    private function convertToArray($arr)
    {
        return [
            "checked"               => false,
            "id"                    => $arr->id,
            "code"                  => $arr->formattedCode(),
//            "payee"                 => $arr->payee(),
            "description"           => $arr->description,
            "total"                 => $arr->total,
            "date"                  => $arr->date,
            "contents"              => json_decode($arr->contents),
            "expenseType"           => $arr->expenseType,
            "sale"                  => $arr->sale,
            "delivery"              => $arr->delivery != null ? new DeliveryResource($arr->delivery) : null,
            "requestForm"           => $arr->requestForm != null ? new RequestFormResource($arr->requestForm) : null,
            "transporter"           => $arr->transporter,
            "supplier"              => $arr->supplier,
        ];
    }

    public function getCodeNumber()
    {
        $last = Payable::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }
}
