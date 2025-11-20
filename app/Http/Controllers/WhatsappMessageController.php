<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsappMessageController extends Controller
{
    public function callback(Request $request){

        return response()->json(['message'=>'Returned status']);
        
    }
}
