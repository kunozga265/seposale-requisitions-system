<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappMessageController extends Controller
{
    public function callback(Request $request)
    {
        Log::info($request->all());
        return response()->json(['message' => 'Returned status']);
    }
}
