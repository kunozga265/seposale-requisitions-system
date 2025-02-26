<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeliveryResource;
use App\Http\Resources\RequestFormResource;
use App\Http\Resources\SaleResource;
use App\Models\Delivery;
use App\Models\Expense;
use App\Models\Payable;
use App\Models\Summary;
use App\Models\Supplier;
use App\Models\SystemLog;
use App\Models\Transporter;
use Barryvdh\DomPDF\Facade\Pdf;
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
        if($filter == "all"){
            $deliveries = Delivery::where("status", ">", 0)->orderBy("due_date","desc")->paginate(100);
            $headline = "all";
        }else if ($filter == "completed"){
            $deliveries = Delivery::where("status", 4)->orderBy("due_date","desc")->paginate(100);
            $headline = "completed";
        }else if ($filter == "cancelled"){
            $deliveries = Delivery::where("status", 3)->orderBy("due_date","desc")->paginate(100);
            $headline = "cancelled";
        }else if ($filter == "delivered"){
            $deliveries = Delivery::where("status", 2)->orderBy("due_date","desc")->paginate(100);
            $headline = "delivered";
        }else{
            $deliveries = Delivery::where("status", 1)->orderBy("due_date","desc")->paginate(100);
            $headline = "processing";
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


    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $delivery = Delivery::find($id);
        $transporters = Transporter::orderBy("name","asc")->get();
        $suppliers = Supplier::orderBy("name","asc")->get();

        if (is_object($delivery)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new DeliveryResource($delivery));
            } else {
                //Web Response
                return Inertia::render('Deliveries/Show', [
                    'delivery' => new DeliveryResource($delivery),
                    'requestForms' => RequestFormResource::collection($delivery->requestForms),
                    'transporters' => $transporters,
                    'suppliers' => $suppliers,
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Delivery not found"], 404);
            } else {
                //Web Response
                return Redirect::route('deliveries.index')->with('error', 'Delivery not found');
            }
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

                if($this->getPaymentStatus($summary->amount,$summary->balance) == 0){
                    return Redirect::back()->with("error", "Product has not been paid for. Please update payment status first.");
                }


                $summary->delivery->update([
                    "code" => $this->getCodeNumber(),
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
                    'photo' => ['required'],
                    'recipient_name' => ['required'],
                    'recipient_phone_number' => ['required'],
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

                $notes =[];
                if($summary->delivery->notes != null){
                    $notes = json_decode($summary->delivery->notes);
                }

                $notes [] = [
                    "quantity" => $request->quantity,
                    "balance" => $balance,
                    "photo" => $request->photo,
                    "recipientName" => $request->recipient_name,
                    "recipientPhoneNumber" => $request->recipient_phone_number,
                    "date"  => Carbon::now()->getTimestamp(),
                ];

                $summary->delivery->update([
                    "status" => $balance == 0 ? 2 : 1,
                    "quantity_delivered" => $delivered_quantity,
                    "notes" => json_encode($notes)
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
                return Redirect::route("deliveries.show",["id"=>$summary->delivery->id])->with('success', 'Delivery updated!');
            }
        } else {
            return Redirect::back()->with('error', 'Delivery not found');
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

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Delivery has been cancelled",
                    "delivery_id" => $delivery->id,
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

    public function complete(Request $request,$id)
    {
        $delivery = Delivery::find($id);

        if (is_object($delivery)) {
            if ((new AppController())->isApi($request)) {
                //API Response
//                return response()->json(new SaleResource($delivery));
            } else {

                $request->validate([
//                    'product' => ['required'],
//                    'transportation' => ['required'],
                ]);

                foreach ($request->payables as $payable){
                    Payable::create([
                        "code" => (new PayableController())->getCodeNumber(),
                        "description" => $payable["name"],
                        "total" => $payable["amount"],
                        "date" => $payable["date"],
                        "contents" => json_encode([]),
                        "expense_type_id" => env('OPERATIONS_EXPENSE_TYPE_ID'),
                        "transporter_id" => $payable["transporterId"] ?? null,
                        "supplier_id" => $payable["supplierId"] ?? null,
                        "delivery_id" => $delivery->id,
                        "sale_id" => $delivery->summary->sale->id,
                        "paid" => false,
                    ]);
                }

                $delivery->update([
                    "status" => 4
                ]);

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Delivery has been completed",
                    "delivery_id" => $delivery->id,
                ]);

                return Redirect::back()->with('success', 'Delivery completed successfully');
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

    public function print(Request $request,$id)
    {
        //find out if the request is valid
        $delivery=Delivery::find($id);

        if(is_object($delivery)){

            $filename="DELIVERY-NOTE#".(new AppController())->getZeroedNumber($delivery->code)." - ".$delivery->summary->sale->client->name."-".date('Ymd',$delivery->summary->sale->date);

            $now_d= \Illuminate\Support\Carbon::createFromTimestamp($delivery->summary->sale->date,'Africa/Lusaka')->format('F j, Y');
            $now_t=Carbon::createFromTimestamp($delivery->summary->sale->date,'Africa/Lusaka')->format('H:i');

            $pdf = PDF::loadView('delivery', [
                'date'              => $now_d,
                'time'              => $now_t,
                'delivery'           => $delivery,
            ]);
            return $pdf->download("$filename.pdf");

        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Delivery not found"], 404);
            }else{


                //Web Response
                return Redirect::route('dashboard')->with('error','Delivery not found');
            }
        }
    }

    public function getCodeNumber()
    {
        $last = Delivery::orderBy("code", "desc")->first();
        if (is_object($last)) {

            return $last->code != null ? $last->code + 1 : 1;
        } else {
            return 1;
        }
    }

    public function getPaymentStatus($amount, $balance): int
    {
//        dump($balance);
        if (isset($balance)) {
            if ($balance == $amount) {
                return 0;
            } elseif ($balance > 0 && $balance < $amount) {
                return 1;
            } elseif ($balance == 0) {
                return 2;
            }
        }
        return 3;
    }
}
