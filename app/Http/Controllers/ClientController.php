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
        $clients = Client::orderBy("name", "desc")->paginate(100);


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
        $client=Client::find($id);

        if(is_object($client)){
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new ClientResource($client));
            }else{
                $sales = $client->sales()->paginate(100);
                //Web Response
                return Inertia::render('Clients/Show',[
                    'client' => new ClientResource($client),
                    'sales' => SaleResource::collection($sales),
                ]);
            }
        }else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Client not found"], 404);
            }else{
                //Web Response
                return Redirect::route('dashboard')->with('error','Client not found');
            }
        }
    }

}
