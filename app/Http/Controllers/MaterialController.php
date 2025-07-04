<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResource;
use App\Models\Batch;
use App\Models\Material;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required"],
            "units" => ["required"],
            // "quantity" => ["required", "numeric", "gt:0"],
            "threshold" => ["required", "numeric", "gt:0"],
            "site_id" => ["required"],
            'materials_type_id' => ['required'],
        ]);

        Material::create([
            "name" => $request->name,
            "units" => $request->units,
            "quantity" => 0,
            "threshold" => $request->threshold,
            "site_id" => $request->site_id,
            "materials_type_id" => $request->materials_type_id,
        ]);

        if ((new AppController())->isApi($request)) {
            //API Response
//                return response()->json(new SiteResource($site));
        } else {

            //Web Response
            return Redirect::back()->with("success", "New Stock Item added!");
        }
    }

    public function update(Request $request)
    {

         //Validate all the important attributes
        $request->validate([
            'total' => ['required',"numeric","gt:0"],
            'quantity' => ['required',"numeric","gt:0"],
            'date' => ['required'],
            'material_id' => ['required'],
           
        ]);

        $material=Material::find($request->material_id);

        if(is_object($material)){

            $quantity = $material->quantity + $request->quantity;

            $material->update([
                'quantity' => $quantity
            ]);

            Batch::create([
                "date" => $request->date,
                "ready_date" => $request->date,
                "price" =>  $request->total/$request->quantity,
                "quantity" => $request->quantity,
                "balance" =>  $request->quantity,
                "accounting_balance" =>  $request->quantity,
                "photo" => $request->photo ?? null,
                "comments" => $request->comments,
                "material_id" => $material->id,
                "user_id" => Auth::id(),
            ]);


            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "New Stock! Added {$request->quantity} to {$material->name}",
                "material_id" => $material->id,
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
                return response()->json(new MaterialResource($material), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', "{$material->name}: Stock updated!");
            }
        }else {
            return Redirect::back()->with('error','Resource not found');
        }
    }
}
