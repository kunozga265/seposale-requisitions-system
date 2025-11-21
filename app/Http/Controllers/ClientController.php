<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\QuotationResource;
use App\Http\Resources\ReceiptResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\SiteSaleResource;
use App\Http\Resources\UserResource;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Sale;
use App\Models\Receipt;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\SiteSale;
use App\Models\Collection;
use App\Models\User;
use App\Models\CustomJob;
use Illuminate\Support\Carbon;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsCheckImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::orderBy("name", "asc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(ClientResource::collection($clients));
        else {
            //Web Response
            return Inertia::render('Clients/Index', [
                'clients' => ClientResource::collection($clients),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $client = Client::find($id);

        if (is_object($client)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new ClientResource($client));
            } else {
                $sales = $client->sales()->orderBy("date", "desc")->get();
                $receipts = $client->receipts()->orderBy("date", "desc")->get();
                $invoices = $client->invoices()->latest()->get();
                $quotations = $client->quotations()->latest()->get();
                $siteSales = $client->siteSales()->orderBy("date", "desc")->get();
                $collections = $client->collections()->orderBy("date", "desc")->get();
                //Web Response
                return Inertia::render('Clients/Show', [
                    'client' => new ClientResource($client),
                    'sales' => SaleResource::collection($sales),
                    'receipts' => ReceiptResource::collection($receipts),
                    'invoices' => InvoiceResource::collection($invoices),
                    'quotations' => QuotationResource::collection($quotations),
                    'siteSales' => SiteSaleResource::collection($siteSales),
                    'collections' => CollectionResource::collection($collections),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Client not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Client not found');
            }
        }
    }


    public function create(Request $request)
    {
        $types = ClientType::orderBy("name", "asc")->get();
        return Inertia::render('Clients/Create', [
            "clientTypes" => $types
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'phoneNumber' => ['required'],
            'client_type_id' => ['required'],
        ]);

        if ($request->client_type_id == 0) {
            $request->validate([
                'client_type' => ['required'],
            ]);
            $client_type_id = ClientType::create([
                "name" => ucwords($request->client_type)
            ])->id;
        } else {
            $client_type_id = $request->client_type_id;
        }

        if (Client::where("phone_number", $request->phoneNumber)->exists()) {
            $existing_client = Client::where("phone_number", $request->phoneNumber)->first();
            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(["message" => "Client with that phone number exists: {$existing_client->getName()}"], 400);
            else {
                //Web Response
                return Redirect::back()->with('error', "Client with that phone number exists: {$existing_client->getName()}");
            }
        }

        $client = Client::create([
            'serial' => (new AppController())->generateUniqueCode("CLIENT"),
            'name' => ucwords($request->name),
            'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
            'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
            'email' => $request->email,
            'address' => $request->address,
            'organisation' => $request->organisation,
            'alias' => $request->alias,
            'client_type_id' => $client_type_id,
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ClientResource($client), 201);
        else {
            //Web Response
            return Redirect::route('clients.index')->with('success', 'Client created!');
        }
    }

    public function getOrCreate($name, $phone_number, $phone_number_other = null, $email = null)
    {
        $client = Client::where('phone_number', $phone_number)->first();

        if (!is_object($client)) {
            $client = Client::create([
                'serial' => (new AppController())->generateUniqueCode("CLIENT"),
                'name' => ucwords($name),
                'phone_number' => (new ClientController())->cleanPhoneNumber($phone_number),
                'phone_number_other' => (new ClientController())->cleanPhoneNumber($phone_number_other),
                'email' => $email,
                'organisation' => false,
                'client_type_id' => 7,
            ]);
        }

        return $client;
    }

    public function edit(Request $request, $id)
    {
        $client = Client::find($id);

        if (is_object($client)) {

            $types = ClientType::orderBy("name", "asc")->get();
            return Inertia::render('Clients/Edit', [
                'client' => new ClientResource($client),
                "clientTypes" => $types
            ]);
        } else {
            return Redirect::back()->with('error', 'Client not found');
        }
    }
    public function merge(Request $request)
    {

        $ids = [];
        if ($request->query('ids') != null) {
            $ids = $request->query('ids');
        }

        if (count($ids) <= 0) {

            return Redirect::back()->with('error', 'Clients to merge not found');
        }

        $clients = Client::whereIn('id', $ids)->get();

        $types = ClientType::orderBy("name", "asc")->get();
        return Inertia::render('Clients/Merge', [
            'clients' => $clients,
            "clientTypes" => $types
        ]);
    }

    public function mergeList(Request $request)
    {

        $request->validate([
            'ids' => ['required'],
            'name' => ['required'],
            'phoneNumber' => ['required'],
            'client_type_id' => ['required'],
        ]);

        if ($request->client_type_id == 0) {
            $request->validate([
                'client_type' => ['required'],
            ]);
            $client_type_id = ClientType::create([
                "name" => ucwords($request->client_type)
            ])->id;
        } else {
            $client_type_id = $request->client_type_id;
        }

        $client = Client::create([
            'serial' => (new AppController())->generateUniqueCode("CLIENT"),
            'name' => ucwords($request->name),
            'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
            'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
            'email' => $request->email,
            'address' => $request->address,
            'organisation' => $request->organisation,
            'alias' => $request->alias,
            'client_type_id' => $client_type_id,
        ]);


        //replace all client references
        Sale::whereIn('client_id', $request->ids)->update([
            'client_id' => $client->id,
        ]);
        Receipt::whereIn('client_id', $request->ids)->update([
            'client_id' => $client->id,
        ]);
        Invoice::whereIn('client_id', $request->ids)->update([
            'client_id' => $client->id,
        ]);
        Quotation::whereIn('client_id', $request->ids)->update([
            'client_id' => $client->id,
        ]);
        SiteSale::whereIn('client_id', $request->ids)->update([
            'client_id' => $client->id,
        ]);
        Collection::whereIn('client_id', $request->ids)->update([
            'client_id' => $client->id,
        ]);

        //delete client objects
        Client::whereIn('id', $request->ids)->delete();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ClientResource($client), 201);
        else {
            //Web Response
            return Redirect::route('clients.index')->with('success', 'Client merged!');
        }
    }

    public function update(Request $request, $id)
    {

        $client = Client::find($id);

        if (is_object($client)) {

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
                'client_type_id' => ['required'],
            ]);

            if ($request->client_type_id == 0) {
                $request->validate([
                    'client_type' => ['required'],
                ]);
                $client_type_id = ClientType::create([
                    "name" => ucwords($request->client_type)
                ])->id;
            } else {
                $client_type_id = $request->client_type_id;
            }


            $client->update([
                'name' => ucwords($request->name),
                'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
                'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
                'email' => $request->email,
                'address' => $request->address,
                'organisation' => $request->organisation,
                'alias' => $request->alias,
                'client_type_id' => $client_type_id,
            ]);


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new ClientResource($client), 201);
            else {
                //Web Response
                return Redirect::route('clients.index')->with('success', 'Client updated!');
            }
        } else {
            return Redirect::back()->with('error', 'Quotation not found');
        }
    }

    public function pricelist(Request $request)
    {
        $users = User::orderBy("firstName", 'asc')->get();
        $clients = Client::orderBy("name", 'asc')->get();
        $types = ClientType::orderBy("name", "asc")->get();
        return Inertia::render('Clients/Pricelist', [
            "clients" => ClientResource::collection($clients),
            "clientTypes" => $types,
            "users" => UserResource::collection($users),
        ]);
    }

    public function pricelistSend(Request $request)
    {
        if ($request->type == 'upload') {

            $request->validate([
                'file' => ['required'],
            ]);

            $base64String = $request->file;

            // Remove the "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," part if it exists
            if (strpos($base64String, ',') !== false) {
                $base64String = explode(',', $base64String)[1];
            }

            // Decode base64 to binary
            $fileData = base64_decode($base64String);

            // Generate a temporary filename
            $tempFile = 'temp_excel_' . time() . '.xlsx';

            try {

                // Store the file temporarily in storage/app
                Storage::disk('temp')->put($tempFile, $fileData);

                // Import using Maatwebsite
                Excel::import(new ClientsCheckImport(), storage_path('app/temp/' . $tempFile));

                // Delete temp file after import
                Storage::disk('temp')->delete($tempFile);

                //Upload File
                $filename = 'files/list-of-clients/' . uniqid() . '.xlsx';
                Storage::disk('public_uploads')->put($filename, $fileData);

                CustomJob::create([
                    "status" => 0,
                    "type" => "PRICELIST_SEND",
                    "content" => json_encode([
                        'file' => $filename,
                        'referred_by_id' => $request->user_id,
                        'user_id' => Auth::id(),
                    ]),
                ]);
            } catch (\Exception $e) {
                Storage::disk('temp')->delete($tempFile);
                // dd($e);

                Log::error($e);
                return Redirect::back()->with('error', "An error occurred: {$e->getMessage()}");
            }

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(['message' => 'Clients uploaded and pricelists will be sent.'], 201);
            else {
                //Web Response
                return Redirect::back()->with('success', 'Clients uploaded and pricelists will be sent.');
            }
        } else {

            //get client info
            if (isset($request->client_id)) {
                $request->validate([
                    'client_id' => ['required'],
                    'referred' => ['required'],
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
                    // 'client_type_id' => ['required'],
                    'phoneNumber' => ['required'],
                ]);

                $client_type_id = 7;

                if (isset($request->client_type_id)) {
                    if ($request->client_type_id == 0) {
                        $request->validate([
                            'client_type' => ['required'],
                        ]);
                        $client_type_id = ClientType::create([
                            "name" => ucwords($request->client_type)
                        ])->id;
                    } else {
                        $client_type_id = $request->client_type_id;
                    }
                }

                if (Client::where("phone_number", $request->phoneNumber)->exists()) {
                    $existing_client = Client::where("phone_number", $request->phoneNumber)->first();
                    if ((new AppController())->isApi($request))
                        //API Response
                        return response()->json(["message" => "Client with that phone number exists: {$existing_client->getName()}"], 400);
                    else {
                        //Web Response
                        return Redirect::back()->with('error', "Client with that phone number exists: {$existing_client->getName()}");
                    }
                }

                $client = Client::create([
                    'serial' => (new AppController())->generateUniqueCode("CLIENT"),
                    'name' => ucwords($request->name),
                    'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
                    'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
                    'email' => $request->email,
                    'address' => $request->address,
                    'organisation' => $request->organisation,
                    'alias' => $request->alias,
                    'client_type_id' => $client_type_id,
                ]);
            }

            //send the pricelist
            if ($request->referred) {

                $request->validate([
                    'user_id' => ['required'],
                ]);
                $name = User::find($request->user_id)->fullName();
                $message = "You have been referred to us by {$name}.";

                Referral::create([
                    'date' => Carbon::now()->getTimestamp(),
                    'referred_by_id' => $request->user_id,
                    'client_id' => $client->id,
                    'user_id' => Auth::id(),
                ]);
            } else {
                $message = "Quality products and services are guaranteed.";
            }

            (new NotificationController())->processWhatsappMessage("pricelist_referred", $client->serial, $message);


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new ClientResource($client), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', 'Pricelist sent!');
            }
        }
    }


    public function cleanPhoneNumber($subject)
    {
        if (isset($subject)) {
            //remove every space
            $number = trim(str_replace(" ", "", $subject));
            //remove plus sign
            $number = trim(str_replace("+", "", $number));
            //if local number, replace with the right code
            if ($number[0] === "0") {
                $number[0] = "-";
                $number = str_replace("-", "", $number);
                $number = "265{$number}";
            }

            return $number;
        } else {
            return null;
        }
    }
}
