<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\QuotationResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\UserResource;
use App\Models\Account;
use App\Models\Client;
use App\Models\Delivery;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Quotation;
use App\Models\Receipt;
use App\Models\Sale;
use App\Models\Summary;
use App\Models\SystemLog;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Rmunate\Utilities\SpellNumber;

class SaleController extends Controller
{
    private $paginate = 100;

    public function index(Request $request, $section)
    {

        $filter = strtolower($request->query("filter"));
        if ($filter == "unpaid") {
            $sales = Sale::where("status", 0)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "unpaid";
        } else if ($filter == "partially-paid") {
            $sales = Sale::where("status", 1)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "partially-paid";
        } else if ($filter == "fully-paid") {
            $sales = Sale::where("status", 2)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "fully-paid";
        } else if ($filter == "closed") {
            $sales = Sale::where("status", 3)->orderBy("date", "desc")->paginate($this->paginate);
            $headline = "closed";
        } else if ($filter == "discarded") {
            $sales = Sale::onlyTrashed()->paginate($this->paginate);
            $headline = "discarded";
        } else {
            $sales = Sale::orderBy("date", "desc")->paginate($this->paginate);
            $headline = "all";
        }

        $unsorted = Sale::orderBy("date", "desc")->get();
        $sorted = [];

        if (!$unsorted->isEmpty()) {
            $currentMonth = date('F', $unsorted[0]->date);
            $currentYear = date('Y', $unsorted[0]->date);

            $item = 0;
            $index = 0;
            foreach ($unsorted as $sale) {

                if ($item == 0) {
                    $sum = 0;
                    foreach ($sale->products as $summary) {
                        if ($summary->getPaymentStatus() > 0) {
                            $sum += $summary->amount;
                        }
                    }
                    $sorted[0] = [
                        'month' => $currentMonth,
                        'year' => $currentYear,
                        'total' => $sum
                    ];
                } else {
                    $month = date('F', $unsorted[$item]->date);
                    $year = date('Y', $unsorted[$item]->date);

                    if ($currentMonth === $month && $currentYear === $year) {
                        $sum = $sorted[$index]['total'];
                        foreach ($sale->products as $summary) {
                            if ($summary->getPaymentStatus() > 0) {
                                $sum += $summary->amount;
                            }
                        }
                        $sorted[$index]['total'] = $sum;
                    } else {
                        $index += 1;
                        $currentMonth = date('F', $unsorted[$item]->date);
                        $currentYear = date('Y', $unsorted[$item]->date);

                        $sum = 0;
                        foreach ($sale->products as $summary) {
                            if ($summary->getPaymentStatus() > 0) {
                                $sum += $summary->amount;
                            }
                        }

                        $sorted[$index] = [
                            'month' => $currentMonth,
                            'year' => $currentYear,
                            'total' => $sum
                        ];
                    }
                }
                $item += 1;
            }
        }

        $chartData = [];
        $currentYear = "";
        $index = -1;
        foreach ($sorted as $item) {
            $year = $item["year"];
            if ($currentYear != $year) {
                $index++;
                $currentYear = $year;
                $chartData[$index] = [
                    "year" => $currentYear,
                    "data" => [$item]
                ];
            } else {
                $chartData[$index]["data"][] = $item;
            }
        }

        for ($i = 0; $i < count($chartData); $i++) {
            $chartData[$i]["data"] = array_reverse($chartData[$i]["data"]);
        }

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(SaleResource::collection($sales));
        else {
            //Web Response
            return Inertia::render('Sales/Index', [
                'sales' => SaleResource::collection($section == "block" ? $sales : $unsorted),
                'headline' => $headline,
                'section' => $section,
                'chartData' => $chartData
            ]);
        }
    }

    public function create(Request $request)
    {
        $products = Product::where("id", "!=", (new AppController())->OTHER_PRODUCT_ID)->orderBy("name", 'asc')->get();
        $clients = Client::orderBy("name", 'asc')->get();
        return Inertia::render('Sales/Create', [
            "products" => ProductResource::collection($products),
            "clients" => ClientResource::collection($clients),
        ]);
    }

    public function store(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        $sale = Cache::lock($user->id . ':sales:store', 10)->get(function () use ($user, $request) {

            //Validate all the important attributes
            $request->validate([
                'products' => ['required'],
                'total' => ['required'],
            ]);

            //get client info
            if (isset($request->client_id)) {
                $request->validate([
                    'client_id' => ['required'],
                ]);

                $client = Client::find($request->client_id);
                if (!is_object($client)) {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "Client not found"], 404);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'Client not found');
                    }
                }
            } else {
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


            $sale = Sale::create([
                'serial' => (new AppController())->generateUniqueCode("SALE"),
                'code_alt' => $this->getSaleCodeNumber(),
                'status' => 0,
                'client_id' => $client->id,
                'total' => $request->total,
                'balance' => $request->total,
                'date' => isset($request->date) ? $request->date : Carbon::now()->getTimestamp(),
                'editable' => true,
                'location' => $request->location,
                'recipient_name' => $request->recipient_name,
                'recipient_profession' => $request->recipient_profession,
                'recipient_phone_number' => $request->recipient_phone_number,

                //Generated by
                'user_id' => $user->id,
            ]);

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "Sale #{$sale->code_alt} created for {$client->name}. Total amount is {$sale
        ->total}",
                "sale_id" => $sale->id,
            ]);

            //attach products
            foreach ($request->products as $product) {
//            $product_model = Product::find($product["id"]);
                $product_variant = ProductVariant::find($product["id"]);
//            if (!is_object($product_variant)) {
//                $product_model = Product::create([
//                    "name" => $product["details"],
//                ]);
//
//                $product_variant = ProductVariant::create([
//                    "unit" => $product["units"],
//                    "quantity" => 1,
//                    "cost" => $product["unitCost"],
//                    "product_id" => $product_model->id
//                ]);
//
//                //Logging
//                SystemLog::create([
//                    "user_id" => Auth::id(),
//                    "message" => "New Product ({$product_model->name}) automatically added into the system.",
//                ]);
//            }

                $summary = Summary::create([
                    //If product not found add it under other
                    "product_id" => is_object($product_variant) ? $product_variant->product->id : 7,
                    "product_variant_id" => is_object($product_variant) ? $product_variant->id : 0,
                    "sale_id" => $sale->id,
                    "date" => $sale->date,
                    "amount" => $product["totalCost"],
                    "balance" => $product["totalCost"],
                    "quantity" => $product["quantity"],
                    "description" => $product["details"],
                    "units" => $product["units"],
                ]);

                if ($summary->product->id != (new AppController())->SERVICES_PRODUCT_ID) {
                    Delivery::create([
                        'serial' => (new AppController())->generateUniqueCode("DELIVERY"),
                        "status" => 0,
                        "quantity_delivered" => 0,
                        "summary_id" => $summary->id,
                        "tracking_number" => uniqid()
                    ]);
                }
            }

            return $sale;


        });

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new SaleResource($sale), 201);
        else {
            //Web Response
            return Redirect::route('sales.index',['section'=>'tabular'])->with('success', 'Sale created!');
        }

//        create delivery note


//        //Create Invoice
//        (new InvoiceController())->storeFromSale($sale);

        //Run notifications
//        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


//        $report = (new ReportController())->getCurrentReport();
//        $report->requestForms()->attach($requestForm);


    }

    public function storeFromQuotation(Request $request, $id)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        //find out if the request is valid
        $quotation = Quotation::find($id);

        if (is_object($quotation)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new QuotationResource($quotation));
            } else {

                $sale = Cache::lock($user->id . ':sales:store', 10)->get(function () use ($quotation, $user, $request) {

                    $sale = Sale::create([
                        'serial' => (new AppController())->generateUniqueCode("SALE"),
                        'code_alt' => $this->getSaleCodeNumber(),
                        'status' => 0,
                        'client_id' => $quotation->client->id,
                        'total' => $quotation->total,
                        'balance' => $quotation->total,
                        'date' => Carbon::now()->getTimestamp(),
                        'editable' => true,
                        'location' => $quotation->location,
                        'recipient_name' => $quotation->recipient_name,
                        'recipient_profession' => $quotation->recipient_profession,
                        'recipient_phone_number' => $quotation->recipient_phone_number,

                        //Generated by
                        'user_id' => $user->id,
                    ]);

                    //Logging
                    SystemLog::create([
                        "user_id" => Auth::id(),
                        "message" => "Sale #{$sale->code_alt} created for {$sale->client->name} from Quotation #{$quotation->code}. Total amount is {$sale
        ->total}",
                        "sale_id" => $sale->id,
                    ]);

                    $products = json_decode($quotation->information);

                    //attach products
                    foreach ($products as $product) {
//            $product_model = Product::find($product["id"]);
                        $product_variant = ProductVariant::find($product->id);
//                    if (!is_object($product_variant)) {
//                        $product_model = Product::create([
//                            "name" => $product->details,
//                        ]);
//
//                        $product_variant = ProductVariant::create([
//                            "unit" => $product->units,
//                            "quantity" => 1,
//                            "cost" => $product->unitCost,
//                            "product_id" => $product_model->id
//                        ]);
//
//                        //Logging
//                        SystemLog::create([
//                            "user_id" => Auth::id(),
//                            "message" => "New Product ({$product_model->name}) automatically added into the system.",
//                        ]);
//                    }

                        $summary = Summary::create([
                            "product_id" => is_object($product_variant) ? $product_variant->product->id : 7,
                            "product_variant_id" => is_object($product_variant) ? $product_variant->id : 0,
                            "sale_id" => $sale->id,
                            "date" => $sale->date,
                            "amount" => $product->totalCost,
                            "balance" => $product->totalCost,
                            "quantity" => $product->quantity,
                            "description" => $product->details,
                            "units" => $product->units,
                        ]);

                        if ($summary->product->id != (new AppController())->SERVICES_PRODUCT_ID) {
                            Delivery::create([
                                'serial' =>  (new AppController())->generateUniqueCode("DELIVERY"),
                                "status" => 0,
                                "quantity_delivered" => 0,
                                "summary_id" => $summary->id,
                                "tracking_number" => uniqid()
                            ]);
                        }

                    }

                    $quotation->update([
                        "sale_id" => $sale->id
                    ]);

                    return $sale;

                });

//                //Create Invoice
//                (new InvoiceController())->storeFromSale($sale);


                //Web Response
                return Redirect::route("sales.show", ["id" => $sale->id])->with("success", "Sale created!");
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Quotation not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Quotation not found');
            }
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $sale = sale::withTrashed()->find($id);
        $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
        $accounts = Account::all();
        $users = User::orderBy("firstName")->get();

        if (is_object($sale)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new SaleResource($sale));
            } else {
                //Web Response
                return Inertia::render('Sales/Show', [
                    'sale' => new SaleResource($sale),
                    'paymentMethods' => $payment_methods,
                    'accounts' => $accounts,
                    'users' => UserResource::collection($users),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "sale not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Sale not found');
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $sale = sale::find($id);

        if (is_object($sale)) {

            $products = Product::where("id", "!=", (new AppController())->OTHER_PRODUCT_ID)->orderBy("name", 'asc')->get();
            $clients = Client::orderBy("name", 'asc')->get();
            return Inertia::render('Sales/Edit', [
                'sale' => new SaleResource($sale),
                "products" => ProductResource::collection($products),
                "clients" => ClientResource::collection($clients),
            ]);
        } else {
            return Redirect::back()->with('error', 'Sale not found');
        }
    }

    public function update(Request $request, $id)
    {

        $user = (new AppController())->getAuthUser($request);
        $sale = sale::find($id);

        if (is_object($sale)) {

            Cache::lock($user->id . ':sales:update', 10)->get(function () use ($user, $request, $sale) {

                //Validate all the important attributes
                $request->validate([
                    'products' => ['required'],
                    'total' => ['required'],
                ]);

                //get client info
                if (isset($request->client_id)) {
                    $request->validate([
                        'client_id' => ['required'],
                    ]);

                    $client = Client::find($request->client_id);
                    if (!is_object($client)) {
                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(['message' => "Client not found"], 404);
                        } else {
                            //Web Response
                            return Redirect::back()->with('error', 'Client not found');
                        }
                    }
                } else {
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

                $sale->update([
                    'client_id' => $client->id,
                    'total' => $request->total,
                    'balance' => $request->total,
                    'date' => $request->date ?? $sale->date,
                    'location' => $request->location,
                    'recipient_name' => $request->recipient_name,
                    'recipient_profession' => $request->recipient_profession,
                    'recipient_phone_number' => $request->recipient_phone_number,
                ]);

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Sale #{$sale->code_alt} updated. Total amount is now {$sale->total}",
                    "sale_id" => $sale->id,
                ]);

                //detach products
                foreach ($sale->products as $product) {
                    if (isset($product->delivery)) {
                        $product->delivery->delete();
                    }
                    $product->delete();
                }
                //attach products
                foreach ($request->products as $product) {
                    $product_variant = ProductVariant::find($product["id"]);
//                if (!is_object($product_variant)) {
//                    $product_model = Product::create([
//                        "name" => $product["details"],
//                    ]);
//
//                    $product_variant = ProductVariant::create([
//                        "unit" => $product["units"],
//                        "quantity" => 1,
//                        "cost" => $product["unitCost"],
//                        "product_id" => $product_model->id
//                    ]);
//
//                    //Logging
//                    SystemLog::create([
//                        "user_id" => Auth::id(),
//                        "message" => "New Product ({$product_model->name}) automatically added into the system.",
//                    ]);
//                }

                    $summary = Summary::create([
                        "product_id" => is_object($product_variant) ? $product_variant->product->id : 7,
                        "product_variant_id" => is_object($product_variant) ? $product_variant->id : 0,
                        "sale_id" => $sale->id,
                        "date" => $sale->date,
                        "amount" => $product["totalCost"],
                        "balance" => $product["totalCost"],
                        "quantity" => $product["quantity"],
                        "description" => $product["details"],
                        "units" => $product["units"],
                    ]);

                    if ($summary->product->id != (new AppController())->SERVICES_PRODUCT_ID) {
                        Delivery::create([
                            'serial' =>  (new AppController())->generateUniqueCode("DELIVERY"),
                            "status" => 0,
                            "quantity_delivered" => 0,
                            "summary_id" => $summary->id,
                            "tracking_number" => uniqid()
                        ]);
                    }

                }

                //Update Invoice
                if ($sale->invoice) {
                    (new InvoiceController())->updateFromSale($sale);
                }
                return true;
            });

            //Run notifications
//        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


//        $report = (new ReportController())->getCurrentReport();
//        $report->requestForms()->attach($requestForm);

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new SaleResource($sale), 201);
            else {
                //Web Response
                return Redirect::route('sales.index',['section'=>'tabular'])->with('success', 'Sale updated!');
            }
        } else {
            return Redirect::back()->with('error', 'Sale not found');
        }
    }


    public function destroy(Request $request, $id)
    {
        //find out if the request is valid
        $sale = sale::find($id);

        if (is_object($sale)) {

            //detach products
            foreach ($sale->products as $product) {
                $product->delete();
            }

            if (is_object($sale->quotation)) {
                $sale->quotation->update([
                    "sale_id" => null
                ]);
            }

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "Sale #{$sale->code_alt} has been deleted.",
                "sale_id" => $sale->id,
            ]);

            $sale->delete();

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => 'Sale has been deleted']);
            } else {
                //Web Response
                return Redirect::route('sales.index',['section'=>'tabular'])->with('success', 'Sale has been deleted');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "sale not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Sale not found');
            }
        }
    }


    public function close(Request $request, $id)
    {
        //find out if the request is valid
        $sale = sale::find($id);

        if (is_object($sale)) {

            $sale->update([
                "status" => 3
            ]);

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "Sale #{$sale->code_alt} has been forcefully closed.",
                "sale_id" => $sale->id,
            ]);

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => 'Sale has been closed']);
            } else {
                //Web Response
                return Redirect::route('sales.index',['section'=>'tabular'])->with('success', 'Sale has been closed');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Sale not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Sale not found');
            }
        }
    }

    public function print(Request $request, $id)
    {
        //find out if the request is valid
        $sale = Sale::find($id);

        if (is_object($sale)) {

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $filename = "SALE#LL" . (new AppController())->getZeroedNumber($sale->code_alt) . " - " . $sale->client->name . "-" . date('Ymd', $sale->date);

            $now_d = \Illuminate\Support\Carbon::createFromTimestamp($sale->date, 'Africa/Lusaka')->format('F j, Y');
            $now_t = Carbon::createFromTimestamp($sale->date, 'Africa/Lusaka')->format('H:i');

            $total_in_words = SpellNumber::value($sale->total)
                ->locale('en')
                ->currency('Kwacha')
                ->fraction('Tambala')
                ->toMoney();

            $total_in_words = str_replace(" of ", " ", $total_in_words);

            $pdf = PDF::loadView('sale', [
                'code' => "LL" . (new AppController())->getZeroedNumber($sale->code_alt),
                'date' => $now_d,
                'time' => $now_t,
                'sale' => $sale,
                'total_in_words' => $total_in_words
            ]);
            return $pdf->download("$filename.pdf");

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

    public function getSaleCodeNumber()
    {
        $last = Sale::orderBy("code_alt", "desc")->first();
        if (is_object($last)) {
            return intval($last->code_alt) + 1;
        } else {
            return 1;
        }
    }
}
