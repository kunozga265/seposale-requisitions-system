<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\RequestFormResource;
use App\Http\Resources\DeliveryResource;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        //find out if the request is valid
        $deliveries = Delivery::orderBy("due_date", "desc")->paginate((new AppController())->paginate);;
       return response()->json(DeliveryResource::collection($deliveries));
    }
    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $delivery = Delivery::find($id);
       

        if (is_object($delivery)) {

            if ($delivery->status == 0) {
                 return response()->json(['message' => "Delivery not initiated"], 404);
            } else {
                return response()->json(new DeliveryResource($delivery));
            }
        } else {
            return response()->json(['message' => "Delivery not found"], 404);
        }
    }

    public function getRequisitions(Request $request, $id)
    {
        //find out if the request is valid
        $delivery = Delivery::find($id);
       

        if (is_object($delivery)) {

            if ($delivery->status == 0) {
                 return response()->json(['message' => "Delivery not initiated"], 404);
            } else {
                return response()->json( RequestFormResource::collection($delivery->requestForms));
            }
        } else {
            return response()->json(['message' => "Delivery not found"], 404);
        }
    }
}
