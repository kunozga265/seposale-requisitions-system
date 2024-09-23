<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\SaleResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::orderBy("name", "asc")->paginate(100);


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
                $sales = $client->sales()->paginate(100);
                //Web Response
                return Inertia::render('Clients/Show', [
                    'client' => new ClientResource($client),
                    'sales' => SaleResource::collection($sales),
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
            'name' => ucwords($request->name),
            'phone_number' => $request->phoneNumber,
            'email' => $request->email,
            'address' => $request->address,
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
                'phone_number' => $request->phoneNumber,
                'email' => $request->email,
                'address' => $request->address,
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


}
