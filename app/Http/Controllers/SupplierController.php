<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SupplierController extends Controller
{
     public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'phone_number' => ['required'],
        ]);

        $transporter = Supplier::create([
            "name" => $request->name,
            "phone_number" => (new ClientController())->cleanPhoneNumber($request->phone_number),
            "phone_number_other" => (new ClientController())->cleanPhoneNumber($request->phone_number_other),
            "email" =>  $request->email,
            "address" => $request->address,
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new SupplierResource($transporter), 201);
        else {
            //Web Response
            return Redirect::route('transporters.index')->with('success', 'Transporter created!');
        }
    }
}
