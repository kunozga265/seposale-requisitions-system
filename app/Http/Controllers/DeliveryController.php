<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryResource;
use App\Http\Resources\SaleResource;
use App\Models\Delivery;
use App\Models\Summary;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DeliveryController extends Controller
{

    public function index(Request $request)
    {
        $filter = strtolower($request->query("filter"));
        if($filter == "processing"){
            $deliveries = Delivery::where("status", 1)->orderBy("due_date","asc")->paginate(100);
            $headline = "processing";
        }else if ($filter == "completed"){
            $deliveries = Delivery::where("status", 2)->orderBy("due_date","asc")->paginate(100);
            $headline = "completed";
        }else if ($filter == "cancelled"){
            $deliveries = Delivery::where("status", 3)->orderBy("due_date","asc")->paginate(100);
            $headline = "cancelled";
        }else{
            $deliveries = Delivery::where("status", ">", 0)->orderBy("due_date","asc")->paginate(100);
            $headline = "all";
        }

//        dd((new AppController())->generateCompound(DeliveryResource::collection($deliveries)));

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(DeliveryResource::collection($deliveries));
        else {
            //Web Response
            return Inertia::render('Deliveries/Index', [
//                'deliveries' => (new AppController())->generateCompound(DeliveryResource::collection($deliveries)),
                'deliveries' => DeliveryResource::collection($deliveries),
                'headline'=>$headline
            ]);
        }
    }

    public function update(Request $request, $id)
    {

        $summary = Summary::find($id);

        //get user
        $user = (new AppController())->getAuthUser($request);


        if (is_object($summary)) {

            if($summary->delivery->status == 0){
                //Validate all the important attributes
                $request->validate([
                    'delivery_date' => ['required'],
                ]);


                $summary->delivery->update([
                    "status" => 1,
                    "due_date" => $request->delivery_date,
//                    "initiated_by" => isset($request->user_id) ? $request->user_id : $user->id
                ]);

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Delivery for {$summary->fullName()} has been initiated. Quantity to be delivered is {$summary->quantity}",
                    "delivery_id" => $summary->delivery->id,
                ]);

            }else{
                //Validate all the important attributes
                $request->validate([
                    'quantity' => ['required'],
                ]);



                $delivered_quantity = $summary->delivery->quantity_delivered;
                $balance = $summary->quantity - $delivered_quantity;
                if($request->quantity == 0){
                    return Redirect::back()->with("error", "Please enter quantity delivered");
                }else if($balance < $request->quantity){
                    return Redirect::back()->with("error", "Quantity is more than what is required");
                }else{
                    $balance -= $request->quantity;
                    $delivered_quantity += $request->quantity;
                }

                $summary->delivery->update([
                    "status" => $balance == 0 ? 2 : 1,
                    "quantity_delivered" => $delivered_quantity
                ]);

                $message = $summary->delivery->status == 2 ?
                    "{$request->quantity} delivered. Delivery Completed." :
                    "{$request->quantity} delivered. {$balance} is yet to be delivered.";

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => $message,
                    "delivery_id" => $summary->delivery->id,
                ]);
            }

            $summary->sale->update([
                "editable" => false
            ]);

            $filter = $summary->delivery->status == 1 ? "processing" : "completed";

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new SaleResource($summary), 201);
            else {
                //Web Response
                return Redirect::route("deliveries.index",["filter"=>$filter])->with('success', 'Delivery updated!');
            }
        } else {
            return Redirect::back()->with('error', 'Sale not found');
        }
    }

    public function cancel(Request $request,$id)
    {
        $delivery = Delivery::find($id);

        if (is_object($delivery)) {
            if ((new AppController())->isApi($request)) {
                //API Response
//                return response()->json(new SaleResource($delivery));
            } else {

                $delivery->update([
                   "status" => 3
                ]);

                return Redirect::back()->with('success', 'Delivery cancelled successfully');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Delivery not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Delivery not found');
            }
        }
    }
}
