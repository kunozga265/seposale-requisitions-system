<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy("name", "asc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(ProductResource::collection($products));
        else {
            //Web Response
            return Inertia::render('Products/Index', [
                'products' => ProductResource::collection($products),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $product = Product::find($id);

        if (is_object($product)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new ProductResource($product));
            } else {
//                $sales = $product->sales()->paginate(100);
                //Web Response
                return Inertia::render('Products/Show', [
                    'product' => new ProductResource($product),
//                    'sales' => ProductResource::collection($sales),
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
        return Inertia::render('Products/Create', [
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'cost' => ['required'],
        ]);


        $product = Product::create([
            "name" => $request->name,
        ]);

        ProductVariant::create([
            "description" => $request->description,
            "unit" => $request->unit,
            "quantity" => $request->quantity,
            "cost" => $request->cost,
            "product_id"=>$product->id
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ProductResource($product), 201);
        else {
            //Web Response
            return Redirect::route('products.index')->with('success', 'Product created!');
        }
    }

    public function addVariant(Request $request)
    {

        $request->validate([
            'id' => ['required'],
            'description' => ['required'],
            'cost' => ['required'],
        ]);

        ProductVariant::create([
            "description" => $request->description,
            "unit" => $request->unit,
            "quantity" => $request->quantity,
            "cost" => $request->cost,
            "product_id"=>$request->id
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json();
        else {
            //Web Response
            return Redirect::route('products.index')->with('success', 'Product variant added!');
        }
    }

    public function editVariant(Request $request)
    {

        $request->validate([
            'id' => ['required'],
            'cost' => ['required'],
        ]);

        $productVariant = ProductVariant::find($request->id);
        $productVariant->update([
            "cost" => $request->cost,
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json();
        else {
            //Web Response
            return Redirect::route('products.index')->with('success', 'Product price udpated!!');
        }
    }
}
