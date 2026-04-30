<?php

namespace App\Http\Controllers;

use App\Http\Resources\BatchResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\UsageResource;
use App\Models\Batch;
use App\Models\Material;
use App\Models\Site;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MaterialController extends Controller
{
    public function show(Request $request, $code, $id)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            //
            $material = $site->materials()->where("id", $id)->first();

            if (is_object($material)) {
                $section  = strtolower($request->query("section"));


                $batches = [];
                $usages = [];


                switch ($section) {
                    case "batches":
                        //get batches
                        $batches = $material->batches()->orderBy("date", "desc")->get();
                        break;
                    default:
                        $section = "overview";
                        $usages = $material->usages()->orderBy("date", "asc")->get();
                }

                return Inertia::render('Materials/Show', [
                    "site" => $site,
                    "section" => $section,
                    "material" => new MaterialResource($material),
                    "usages" => UsageResource::collection($usages),
                    "batches" => BatchResource::collection($batches),
                ]);
            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Site not found"], 404);
                } else {
                    //Web Response
                    return Redirect::route('dashboard')->with('error', 'Site not found');
                }
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
    }

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
            'total' => ['required', "numeric", "gt:0"],
            'quantity' => ['required', "numeric", "gt:0"],
            'date' => ['required'],
            'material_id' => ['required'],

        ]);


        $material = Material::find($request->material_id);
        if ($material->inventoryAccount->balance < $request->total) {
            return Redirect::back()->with("error", "{$material->name} cost is greater than inventory balance!");
        }

        if (is_object($material)) {

            $quantity = $material->quantity + $request->quantity;

            $material->update([
                'quantity' => $quantity
            ]);

            Batch::create([
                "date" => $request->date,
                "ready_date" => $request->date,
                "price" =>  $request->total / $request->quantity,
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
        } else {
            return Redirect::back()->with('error', 'Resource not found');
        }
    }
}
