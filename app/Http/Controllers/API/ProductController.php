<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\APP\ProductVariantResource;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function trending(){
        $variants = ProductVariant::all();
        return response()->json(['products'=>ProductVariantResource::collection($variants)]);

    }

    public function index (){

    }
}
