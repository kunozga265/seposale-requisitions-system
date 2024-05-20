<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\QuotationResource;
use App\Models\Product;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        $quotations = Quotation::latest()->paginate((new AppController())->paginate);


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
        $products = Product::orderBy("name", 'asc')->get();
        return Inertia::render('Quotations/Create', [
            "products" => ProductResource::collection($products)
        ]);
    }

    public function store(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        //Validate all the important attributes
        $request->validate([
            'name' => ['required'],
            'information' => ['required'],
            'total' => ['required'],
        ]);

        //generate code
        $last_quotation = Quotation::latest()->first();
        if (is_object($last_quotation)){
            $code = $last_quotation->code + 1;
        }else{
            $code = 1;
        }

        $quotation = Quotation::create([
            'code' => $code,

            //Customer Details
            'name' => $request->name,
            'phone_number' => $request->phoneNumber,
            'email' => $request->email,
            'address' => $request->address,
            'location' => $request->location,

            'information' => json_encode($request->information),
            'total' => $request->total,

            //Requested by
            'user_id' => $user->id,
            'quotes' => json_encode($request->quotes ?? []),
        ]);

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

             $products = Product::orderBy("name", 'asc')->get();
            return Inertia::render('Quotations/Edit',[
                'quotation'   => new QuotationResource($quotation),
                 "products" => ProductResource::collection($products)
            ]);
        }else {
            return Redirect::back()->with('error','Quotation not found');
        }
    }

    public function update(Request $request, $id)
    {

        $quotation=Quotation::find($id);

        if(is_object($quotation)){

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
                'information' => ['required'],
                'total' => ['required'],
            ]);

            $quotation->update([
                //Customer Details
                'name' => $request->name,
                'phone_number' => $request->phoneNumber,
                'email' => $request->email,
                'address' => $request->address,
                'location' => $request->location,

                'information' => json_encode($request->information),
                'total' => $request->total,

                //Requested by
                'quotes' => json_encode($request->quotes ?? []),
            ]);

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

            $filename="QUOTATION#".(new AppController())->getQuotationNumber($quotation->code)." - ".$quotation->name."-".date('YMj');

            $now_d=Carbon::now('Africa/Lusaka')->format('F j, Y');
            $now_t=Carbon::now('Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($quotation->total)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            $pdf = PDF::loadView('quotation', [
                'code'          => (new AppController())->getQuotationNumber($quotation->code),
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
