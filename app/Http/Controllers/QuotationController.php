<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\QuotationResource;
use App\Models\Client;
use App\Models\Product;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Rmunate\Utilities\SpellNumber;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
//        $user=(new AppController())->getAuthUser($request);
//
//        if($user->hasRole('management') || $user->hasRole('administrator')){
//            $projects= Project::orderBy('name','asc')->paginate((new AppController())->paginate);
//
//        }else {
//            $projects = Project::orderBy('name', 'asc')->where('verified', 1)->where('status', 1)->paginate((new AppController())->paginate);
//        }

        $quotations = Quotation::latest()->paginate(100);


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(QuotationResource::collection($quotations));
        else {
            //Web Response
            return Inertia::render('Quotations/Index', [
                'quotations' => QuotationResource::collection($quotations),
            ]);
        }
    }

    public function create(Request $request)
    {
        $products = Product::where("id","!=",(new AppController())->OTHER_PRODUCT_ID)->orderBy("name", 'asc')->get();
        $clients = Client::orderBy("name", 'asc')->get();
        return Inertia::render('Quotations/Create', [
            "products" => ProductResource::collection($products),
            "clients" => ClientResource::collection($clients),
        ]);
    }

    private function getCodeNumber()
    {
        $last_invoice = Quotation::orderBy("code","desc")->first();
        if (is_object($last_invoice)){
            return $last_invoice->code + 1;
        }else{
            return 1;
        }
    }

    public function store(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        //Validate all the important attributes
        $request->validate([
            'information' => ['required'],
            'total' => ['required'],
        ]);

        //get client info
        if (isset($request->client_id)){
            $request->validate([
                'client_id' => ['required'],
            ]);

            $client = Client::find($request->client_id);
            if (!is_object($client)){
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Client not found"], 404);
                }else{
                    //Web Response
                    return Redirect::back()->with('error','Client not found');
                }
            }
        }else{
            $request->validate([
                'name' => ['required'],
            ]);

            $client = Client::create([
                'serial' =>  (new AppController())->generateUniqueCode("CLIENT"),
                'name' => $request->name,
                'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
                'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
                'email' => $request->email,
                'address' => $request->address,
                'organisation' => $request->organisation,
                'alias' => $request->alias,
            ]);
        }

        $quotation = Cache::lock($user->id . ':quotation:store', 10)->get(function () use ($client, $user, $request) {

            return Quotation::create([
                'code' => $this->getCodeNumber(),
                'serial' =>  (new AppController())->generateUniqueCode("QUOTATION"),
                //Customer Details
                'client_id' => $client->id,
                'location' => $request->location,
                'recipient_name' => $request->recipient_name,
                'recipient_profession' => $request->recipient_profession,
                'recipient_phone_number' => $request->recipient_phone_number,

                'information' => json_encode($request->information),
                'total' => $request->total,

                //Requested by
                'user_id' => $user->id,
                'quotes' => json_encode($request->quotes ?? []),
            ]);

        });

        //Run notifications
//        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


//        $report = (new ReportController())->getCurrentReport();
//        $report->requestForms()->attach($requestForm);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new QuotationResource($quotation), 201);
        else {
            //Web Response
            return Redirect::route('quotations.index')->with('success', 'Quotation created!');
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $quotation=Quotation::find($id);

        if(is_object($quotation)){
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new QuotationResource($quotation));
            }else{
                //Web Response
                return Inertia::render('Quotations/Show',[
                    'quotation' => new QuotationResource($quotation)
                ]);
            }
        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Quotation not found"], 404);
            }else{
                //Web Response
                return Redirect::route('dashboard')->with('error','Quotation not found');
            }
        }
    }

    public function edit(Request $request,$id)
    {
        $quotation=Quotation::find($id);

        if(is_object($quotation)){

            $products = Product::where("id","!=",(new AppController())->OTHER_PRODUCT_ID)->orderBy("name", 'asc')->get();
             $clients = Client::orderBy("name", 'asc')->get();
            return Inertia::render('Quotations/Edit',[
                'quotation'   => new QuotationResource($quotation),
                 "products" => ProductResource::collection($products),
                "clients" => ClientResource::collection($clients),
            ]);
        }else {
            return Redirect::back()->with('error','Quotation not found');
        }
    }

    public function update(Request $request, $id)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        $quotation=Quotation::find($id);

        if(is_object($quotation)){

            //Validate all the important attributes
            $request->validate([
                'information' => ['required'],
                'total' => ['required'],
            ]);

            //get client info
            if (isset($request->client_id)){
                $request->validate([
                    'client_id' => ['required'],
                ]);

                $client = Client::find($request->client_id);
                if (!is_object($client)){
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "Client not found"], 404);
                    }else{
                        //Web Response
                        return Redirect::back()->with('error','Client not found');
                    }
                }
            }else{
                $request->validate([
                    'name' => ['required'],
                ]);

                $client = Client::create([
                    'serial' =>  (new AppController())->generateUniqueCode("CLIENT"),
                    'name' => $request->name,
                    'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
                    'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
                    'email' => $request->email,
                    'address' => $request->address,
                    'organisation' => $request->organisation,
                    'alias' => $request->alias,
                ]);
            }

            Cache::lock($user->id . ':quotation:update', 10)->get(function () use ($quotation, $client, $user, $request) {

                $quotation->update([
                    //Customer Details
                    'client_id' => $client->id,
                    'location' => $request->location,
                    'recipient_name' => $request->recipient_name,
                    'recipient_profession' => $request->recipient_profession,
                    'recipient_phone_number' => $request->recipient_phone_number,

                    'information' => json_encode($request->information),
                    'total' => $request->total,

                    //Requested by
                    'quotes' => json_encode($request->quotes ?? []),
                ]);

                return true;
            });

            //Run notifications
//        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


//        $report = (new ReportController())->getCurrentReport();
//        $report->requestForms()->attach($requestForm);

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new QuotationResource($quotation), 201);
            else {
                //Web Response
                return Redirect::route('quotations.index')->with('success', 'Quotation updated!');
            }
        }else {
            return Redirect::back()->with('error','Quotation not found');
        }
    }

    public function destroy(Request $request,$id)
    {
        //find out if the request is valid
        $quotation=Quotation::find($id);

        if(is_object($quotation)){

            $quotation->delete();

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message'=>'Quotation has been deleted']);
            }else{
                //Web Response
                return Redirect::route('quotations.index')->with('success','Quotation has been deleted');
            }
        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Quotation not found"], 404);
            }else{
                //Web Response
                return Redirect::back()->with('error','Quotation not found');
            }
        }
    }


    public function print(Request $request,$id)
    {
        //find out if the request is valid
        $quotation=Quotation::find($id);

        if(is_object($quotation)){

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $filename="QUOTATION#".(new AppController())->getZeroedNumber($quotation->code)." - ".$quotation->client->name."-".date('Ymd');

            $now_d=Carbon::createFromTimestamp($quotation->created_at->getTimestamp(),'Africa/Lusaka')->format('F j, Y');
            $now_t=Carbon::createFromTimestamp($quotation->created_at->getTimestamp(),'Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($quotation->total)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            str_replace($total_in_words," and "," of ");

            $pdf = PDF::loadView('quotation', [
                'code'          => (new AppController())->getZeroedNumber($quotation->code),
                'date'          => $now_d,
                'time'          => $now_t,
                'quotation'   => new QuotationResource($quotation),
                'total_in_words' => $total_in_words
            ]);
            return $pdf->download("$filename.pdf");

        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Quotation not found"], 404);
            }else{


                //Web Response
                return Redirect::route('dashboard')->with('error','Quotation not found');
            }
        }
    }
}
