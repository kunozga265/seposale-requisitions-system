<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Http\Resources\ProductResource;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Rmunate\Utilities\SpellNumber;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::latest()->paginate(100);


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(InvoiceResource::collection($invoices));
        else {
            //Web Response
            return Inertia::render('Invoices/Index', [
                'invoices' => InvoiceResource::collection($invoices),
            ]);
        }
    }

    public function create(Request $request)
    {
        $products = Product::orderBy("name", 'asc')->get();
        return Inertia::render('Invoices/Create', [
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

        $invoice = Invoice::create([
            'code' => $this->getCodeNumber(),

            //Customer Details
            'name' => $request->name,
            'phone_number' => $request->phoneNumber,
            'email' => $request->email,
            'address' => $request->address,
            'location' => $request->location,

            'information' => json_encode($request->information),
            'total' => $request->total,
            'status' => 0,

            //Requested by
            'user_id' => $user->id,
            'receipts' => json_encode($request->receipts ?? []),
        ]);

        //Run notifications
//        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


//        $report = (new ReportController())->getCurrentReport();
//        $report->requestForms()->attach($requestForm);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new InvoiceResource($invoice), 201);
        else {
            //Web Response
            return Redirect::route('invoices.index')->with('success', 'Invoice created!');
        }
    }

    private function getCodeNumber()
    {
        $last_invoice = Invoice::orderBy("code","desc")->first();
        if (is_object($last_invoice)){
            return $last_invoice->code + 1;
        }else{
            return 1;
        }
    }

    public function storeFromSale(Request $request, $id)
    {
        $sale = sale::find($id);

        if (is_object($sale)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new SaleResource($sale));
            } else {

                $invoice = Invoice::create([
                    'code' => $this->getCodeNumber(),
                    'revision' => 0,
                    'client_id' => $sale->client->id,
                    'sale_id' => $sale->id,
                ]);

                return Redirect::route("invoices.show",["id"=>$invoice->id])->with("success","Invoice generated");
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Sale not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Sale not found');
            }
        }
    }

    public function updateFromSale(Sale $sale)
    {
        $revision = $sale->invoice->revision + 1;

        $sale->invoice->update([
            'revision' => $revision,
            'client_id' => $sale->client->id,
        ]);
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $invoice=Invoice::find($id);

        if(is_object($invoice)){
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new InvoiceResource($invoice));
            }else{
                //Web Response
                return Inertia::render('Invoices/Show',[
                    'invoice' => new InvoiceResource($invoice)
                ]);
            }
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

    public function edit(Request $request,$id)
    {
        $invoice=Invoice::find($id);

        if(is_object($invoice)){

            $products = Product::orderBy("name", 'asc')->get();
            return Inertia::render('Invoices/Edit',[
                'invoice'   => new InvoiceResource($invoice),
                "products" => ProductResource::collection($products)
            ]);
        }else {
            return Redirect::back()->with('error','Invoice not found');
        }
    }

    public function update(Request $request, $id)
    {

        $invoice=Invoice::find($id);

        if(is_object($invoice)){

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
                'information' => ['required'],
                'total' => ['required'],
            ]);

            $invoice->update([
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
                return response()->json(new InvoiceResource($invoice), 201);
            else {
                //Web Response
                return Redirect::route('invoices.index')->with('success', 'Invoice updated!');
            }
        }else {
            return Redirect::back()->with('error','Invoice not found');
        }
    }

    public function destroy(Request $request,$id)
    {
        //find out if the request is valid
        $invoice=Invoice::find($id);

        if(is_object($invoice)){

            $invoice->delete();

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message'=>'Invoice has been deleted']);
            }else{
                //Web Response
                return Redirect::route('invoices.index')->with('success','Invoice has been deleted');
            }
        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Invoice not found"], 404);
            }else{
                //Web Response
                return Redirect::back()->with('error','Invoice not found');
            }
        }
    }


    public function print(Request $request,$id)
    {
        //find out if the request is valid
        $invoice=Invoice::find($id);

        if(is_object($invoice)){

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $filename="INVOICE#".(new AppController())->getZeroedNumber($invoice->code,$invoice->revision)." - ".$invoice->name."-".date('Ymd',$invoice->sale->date);

            $now_d=Carbon::createFromTimestamp($invoice->sale->date,'Africa/Lusaka')->format('F j, Y');
            $now_t=Carbon::createFromTimestamp($invoice->sale->date,'Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($invoice->sale->total)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            $total_in_words = str_replace(" of "," ",$total_in_words);

            $pdf = PDF::loadView('invoice', [
                'code'              => (new AppController())->getZeroedNumber($invoice->code,$invoice->revision),
                'date'              => $now_d,
                'time'              => $now_t,
                'invoice'           => new InvoiceResource($invoice),
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
