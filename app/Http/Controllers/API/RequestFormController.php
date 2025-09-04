<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\RequestFormResource;
use App\Models\RequestForm;
use Illuminate\Http\Request;

class RequestFormController extends Controller
{
    public function index(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        //check role
        if ($user->hasRole('management') || $user->hasRole('administrator')) {
            //Should they get only those approved by management or every single request form?

            $totalRequests = RequestForm::all()->count();

            //For Pie Chart
            $pettyCashRequestsCount = RequestForm::where('type', 'PETTY_CASH')->count();
            $requisitionRequestsCount = RequestForm::where('type', 'REQUISITION')->count();

            //Page Info
            $approvedRequestsCount = RequestForm::where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->count();
            $pendingRequestsCount = RequestForm::where('approvalStatus', 0)->count();
            $deniedRequestsCount = RequestForm::where('approvalStatus', 2)->count();
            $closedRequestsCount = RequestForm::where('approvalStatus', '>', 3)->count();

            //Requests section
            $requests = RequestForm::orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);
            // $closedRequests = RequestForm::where('approvalStatus', '>', 3)->orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);
        } else {
            $totalRequests = $user->requestForms->count();

            //For Pie Chart
            $pettyCashRequestsCount = $user->requestForms()->where('type', 'PETTY_CASH')->count();
            $requisitionRequestsCount = $user->requestForms()->where('type', 'REQUISITION')->count();
            $vehicleMaintenanceRequestsCount = $user->requestForms()->where('type', 'VEHICLE_MAINTENANCE')->count();
            $fuelRequestsCount = $user->requestForms()->where('type', 'FUEL')->count();

            //Page Info
            $approvedRequestsCount = $user->requestForms()->where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->count();
            $pendingRequestsCount = $user->requestForms()->where('approvalStatus', 0)->count();
            $deniedRequestsCount = $user->requestForms()->where('approvalStatus', 2)->count();
            $closedRequestsCount = $user->requestForms()->where('approvalStatus', '>', 3)->count();

            //Requests section
            $requests = $user->requestForms()->orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);
            // $closedRequests = $user->requestForms()->where('approvalStatus', '>', 3)->orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);
        }

        $response = [
            'totalRequests' => $totalRequests,
            'pettyCashRequestsCount' => $pettyCashRequestsCount,
            'requisitionRequestsCount' => $requisitionRequestsCount,
            'vehicleMaintenanceRequestsCount' => $vehicleMaintenanceRequestsCount,
            'fuelRequestsCount' => $fuelRequestsCount,
            'approvedRequestsCount' => $approvedRequestsCount,
            'pendingRequestsCount' => $pendingRequestsCount,
            'deniedRequestsCount' => $deniedRequestsCount,
            'closedRequestsCount' => $closedRequestsCount,
            'requests' => RequestFormResource::collection($requests),

        ];

        return response()->json($response);
    }
}
