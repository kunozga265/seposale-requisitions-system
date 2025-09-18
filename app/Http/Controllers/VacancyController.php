<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Http\Resources\VacancyResource;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VacancyController extends Controller
{
     public function index(Request $request)
    {
        $vacancies = Vacancy::orderBy("date", "desc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(VacancyResource::collection($vacancies));
        else {
            //Web Response
            return Inertia::render('Vacancies/Index', [
                'vacancies' => VacancyResource::collection($vacancies),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $vacancy = Vacancy::find($id);

        if (is_object($vacancy)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new VacancyResource($vacancy));
            } else {
               
                //Web Response
                return Inertia::render('Vacancies/Show', [
                    'vacancy' => new VacancyResource($vacancy),
                    'applications' => ApplicationResource::collection($vacancy->applications),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Vacancy not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Vacancy not found');
            }
        }
    }

    public function application(Request $request, $id)
    {
        //find out if the request is valid
        $application = Application::find($id);

        if (is_object($application)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new VacancyResource($application));
            } else {
               
                //Web Response
                return Inertia::render('Vacancies/Application', [
                   
                    'application' => new ApplicationResource($application),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Application not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Application not found');
            }
        }
    }
}
