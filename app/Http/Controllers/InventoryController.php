<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function update(Request $request)
    {

        $inventory=Inventory::find($request->inventory_id);

        if(is_object($inventory)){

            //Validate all the important attributes
            $request->validate([
                'quantity' => ['required'],
                'date' => ['required'],
            ]);

            $availableStock = $inventory->available_stock + $request->quantity;

            $inventory->update([
                'available_stock' => $availableStock
            ]);

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "New Stock! Added {$request->quantity} to {$inventory->name}",
                "inventory_id" => $inventory->id,
                "contents" => json_encode([
                    "date" => $request->date,
                    "quantity" => $request->quantity,
                    "comments" => $request->comments,
                    "photo" => $request->photo,
                ])
            ]);

            //Run notifications
//        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


//        $report = (new ReportController())->getCurrentReport();
//        $report->requestForms()->attach($requestForm);

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new InventoryResource($inventory), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', "{$inventory->name}: Inventory updated!");
            }
        }else {
            return Redirect::back()->with('error','Resource not found');
        }
    }
}
