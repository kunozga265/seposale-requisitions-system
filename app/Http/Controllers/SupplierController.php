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
            "serial" => (new AppController())->generateUniqueCode("SUPPLIER"),
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


    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => ['required'],
            'phone_number' => ['required'],
        ]);

        $supplier = Supplier::findOrFail($id);

        if (is_object($supplier)) {

            $supplier->update([
                "serial" => (new AppController())->generateUniqueCode("SUPPLIER"),
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
                return Redirect::route('suppliers.index')->with('success', 'Supplier updated!');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Supplier not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Supplier not found');
            }
        }
    }
}
