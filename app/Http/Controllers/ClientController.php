<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\ReceiptResource;
use App\Http\Resources\SaleResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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
                $sales = $client->sales()->orderBy("date","desc")->get();
                $receipts = $client->receipts()->orderBy("date","desc")->get();
                //Web Response
                return Inertia::render('Clients/Show', [
                    'client' => new ClientResource($client),
                    'sales' => SaleResource::collection($sales),
                    'receipts' => ReceiptResource::collection($receipts),
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
        return Inertia::render('Clients/Create', [
        ]);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
        ]);

        $client = Client::create([
            'serial' =>  (new AppController())->generateUniqueCode("CLIENT"),
            'name' => ucwords($request->name),
            'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
            'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
            'email' => $request->email,
            'address' => $request->address,
            'organisation' => $request->organisation,
            'alias' => $request->alias,
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ClientResource($client), 201);
        else {
            //Web Response
            return Redirect::route('clients.index')->with('success', 'Client created!');
        }
    }

    public function edit(Request $request, $id)
    {
        $client = Client::find($id);

        if (is_object($client)) {

            return Inertia::render('Clients/Edit', [
                'client' => new ClientResource($client),
            ]);
        } else {
            return Redirect::back()->with('error', 'Client not found');
        }
    }

    public function update(Request $request, $id)
    {

        $client = Client::find($id);

        if (is_object($client)) {

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
            ]);

            $client->update([
                'name' => ucwords($request->name),
                'phone_number' => (new ClientController())->cleanPhoneNumber($request->phoneNumber),
                'phone_number_other' => (new ClientController())->cleanPhoneNumber($request->phoneNumberOther),
                'email' => $request->email,
                'address' => $request->address,
                'organisation' => $request->organisation,
                'alias' => $request->alias,
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
        }else{
            return null;
        }
    }


}
