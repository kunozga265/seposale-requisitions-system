<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy("name", "asc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(SupplierResource::collection($suppliers));
        else {
            //Web Response
            return Inertia::render('Suppliers/Index', [
                'suppliers' => SupplierResource::collection($suppliers),
            ]);
        }
    }

    public function create(Request $request)
    {
        return Inertia::render('Suppliers/Create', []);
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'phone_number' => ['required'],
        ]);

        $supplier = Supplier::create([
            "name" => $request->name,
            "phone_number" => (new ClientController())->cleanPhoneNumber($request->phone_number),
            "phone_number_other" => (new ClientController())->cleanPhoneNumber($request->phone_number_other),
            "email" =>  $request->email,
            "address" => $request->address,
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new SupplierResource($supplier), 201);
        else {
            //Web Response
            return Redirect::route('suppliers.index')->with('success', 'Supplier created!');
        }
    }
}
