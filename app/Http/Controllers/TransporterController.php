<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransporterResource;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TransporterController extends Controller
{
        public function index(Request $request)
    {
        $transporters = Transporter::orderBy("name", "asc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(TransporterResource::collection($transporters));
        else {
            //Web Response
            return Inertia::render('Transporters/Index', [
                'transporters' => TransporterResource::collection($transporters),
            ]);
        }
    }

    public function create(Request $request)
    {
        return Inertia::render('Transporters/Create', [
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'phone_number' => ['required'],
        ]);

        $transporter = Transporter::create([
            "name" => $request->name,
            "phone_number" => (new ClientController())->cleanPhoneNumber($request->phone_number),
            "phone_number_other" => (new ClientController())->cleanPhoneNumber($request->phone_number_other),
            "email" =>  $request->email,
            "location" => $request->location,
            "registration_number" => $request->registration_number,
            "type" =>  $request->type,
            "make" => $request->make,
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new TransporterResource($transporter), 201);
        else {
            //Web Response
            return Redirect::route('transporters.index')->with('success', 'Transporter created!');
        }
    }
}
