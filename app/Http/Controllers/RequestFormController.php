<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\ReportResource;
use App\Http\Resources\RequestFormResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\VehicleResource;
use App\Models\Account;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Payable;
use App\Models\Position;
use App\Models\Project;
use App\Models\Report;
use App\Models\RequestForm;
use App\Models\Sale;
use App\Models\Summary;
use App\Models\Transaction;
use App\Models\Transporter;
use App\Models\User;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use function PHPUnit\Framework\isEmpty;

/* Approval Statuses
 * 0 -> Pending
 * 1 -> Approved
 * 2 -> Denied
 * 3 -> Initiated
 * 4 -> Reconciled
 * 5 -> Discarded
 */

class RequestFormController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    public function approved(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);
        if ($user->hasRole('management')) {
            $totalRequests = RequestForm::where('approval_by_id', $user->id)->count();

            //For Pie Chart
            $pettyCashRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('type', 'PETTY_CASH')->count();
            $requisitionRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('type', 'REQUISITION')->count();
            $vehicleMaintenanceRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('type', 'VEHICLE_MAINTENANCE')->count();
            $fuelRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('type', 'FUEL')->count();

            //Page Info
            $approvedRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->count();
            $pendingRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('approvalStatus', 0)->count();
            $deniedRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('approvalStatus', 2)->count();
            $closedRequestsCount = RequestForm::where('approval_by_id', $user->id)->where('approvalStatus', '>', 3)->count();

            //Requests section
            $activeRequests = RequestForm::where('approval_by_id', $user->id)->where('approvalStatus', '<', 4)->orderBy('dateRequested', 'desc')->get();
            $closedRequests = RequestForm::where('approval_by_id', $user->id)->where('approvalStatus', '>', 3)->orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);

        } else {
            $totalRequests = $user->approvedRequests->count();

            //For Pie Chart
            $pettyCashRequestsCount = $user->approvedRequests()->where('type', 'PETTY_CASH')->count();
            $requisitionRequestsCount = $user->approvedRequests()->where('type', 'REQUISITION')->count();
            $vehicleMaintenanceRequestsCount = $user->approvedRequests()->where('type', 'VEHICLE_MAINTENANCE')->count();
            $fuelRequestsCount = $user->approvedRequests()->where('type', 'FUEL')->count();

            //Page Info
            $approvedRequestsCount = $user->approvedRequests()->where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->count();
            $pendingRequestsCount = $user->approvedRequests()->where('approvalStatus', 0)->count();
            $deniedRequestsCount = $user->approvedRequests()->where('approvalStatus', 2)->count();
            $closedRequestsCount = $user->approvedRequests()->where('approvalStatus', '>', 3)->count();

            //Requests section
            $activeRequests = $user->approvedRequests()->where('approvalStatus', '<', 4)->orderBy('dateRequested', 'desc')->get();
            $closedRequests = $user->approvedRequests()->where('approvalStatus', '>', 3)->orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);
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
            'activeRequests' => RequestFormResource::collection($activeRequests),
            'closedRequests' => RequestFormResource::collection($closedRequests),
        ];

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json($response);
        else {
            //Web Response
            return Inertia::render('Approved', $response);
        }
    }

    public function finance(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);

        //Requests section
        $totalRequests = RequestForm::where('approvalStatus', '>', 0)->where('approvalStatus', '<', 5)->where('approvalStatus', '!=', 2)->count();

        //For Pie Chart
        $pettyCashRequestsCount = RequestForm::where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->where('type', 'PETTY_CASH')->count();
        $requisitionRequestsCount = RequestForm::where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->where('type', 'REQUISITION')->count();
        $vehicleMaintenanceRequestsCount = RequestForm::where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->where('type', 'VEHICLE_MAINTENANCE')->count();
        $fuelRequestsCount = RequestForm::where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->where('type', 'FUEL')->count();

        $awaitingInitiation = RequestForm::where('approvalStatus', 1)->where("dateRequested",">=",env('TIMESTAMP_CUTOFF'))->get();
        $awaitingReconciliation = RequestForm::where('approvalStatus', 3)->where("dateRequested",">=",env('TIMESTAMP_CUTOFF'))->get();
        $reconciled = RequestForm::where('approvalStatus', 4)->paginate((new AppController())->paginate);

        $awaitingInitiationCount = $awaitingInitiation->count();
        $awaitingReconciliationCount = $awaitingReconciliation->count();
        $reconciledCount = RequestForm::where('approvalStatus', 4)->count();

        $response = [
            'totalRequests' => $totalRequests,
            'pettyCashRequestsCount' => $pettyCashRequestsCount,
            'requisitionRequestsCount' => $requisitionRequestsCount,
            'vehicleMaintenanceRequestsCount' => $vehicleMaintenanceRequestsCount,
            'fuelRequestsCount' => $fuelRequestsCount,
            'awaitingInitiationCount' => $awaitingInitiationCount,
            'awaitingReconciliationCount' => $awaitingReconciliationCount,
            'reconciledCount' => $reconciledCount,
            'awaitingInitiation' => RequestFormResource::collection($awaitingInitiation),
            'awaitingReconciliation' => RequestFormResource::collection($awaitingReconciliation),
            'reconciled' => RequestFormResource::collection($reconciled),
        ];

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json($response);
        else {
            //Web Response
            return Inertia::render('Finance', $response);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pending(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);
        $toInitiate = [];
        $toReconcile = [];

        if ($user->hasRole('management')) {
            $toApprove = RequestForm::where('approvalStatus', 0)->where('stagesApprovalStatus', 1)->where('user_id', '!=', $user->id)->orderBy('dateRequested', 'desc')->get();
        } elseif ($user->hasRole('accountant')) {
            $toReconcile = RequestForm::where('approvalStatus', 3)->orderBy('dateRequested', 'desc')->get();
            $toInitiate = RequestForm::where('approvalStatus', 1)->orderBy('dateRequested', 'desc')->get();
            $toApprove = RequestForm::where('approvalStatus', 0)->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();
        } else {
            $toApprove = RequestForm::where('approvalStatus', 0)->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();
        }

        return response()->json([
            'toApprove' => RequestFormResource::collection($toApprove),
            'toInitiate' => RequestFormResource::collection($toInitiate),
            'toReconcile' => RequestFormResource::collection($toReconcile),
        ]);
    }

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
            $vehicleMaintenanceRequestsCount = RequestForm::where('type', 'VEHICLE_MAINTENANCE')->count();
            $fuelRequestsCount = RequestForm::where('type', 'FUEL')->count();

            //Page Info
            $approvedRequestsCount = RequestForm::where('approvalStatus', '>', 0)->where('approvalStatus', '<', 4)->where('approvalStatus', '!=', 2)->count();
            $pendingRequestsCount = RequestForm::where('approvalStatus', 0)->count();
            $deniedRequestsCount = RequestForm::where('approvalStatus', 2)->count();
            $closedRequestsCount = RequestForm::where('approvalStatus', '>', 3)->count();

            //Requests section
            $activeRequests = RequestForm::where('approvalStatus', '<', 4)->orderBy('dateRequested', 'desc')->get();
            $closedRequests = RequestForm::where('approvalStatus', '>', 3)->orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);
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
            $activeRequests = $user->requestForms()->where('approvalStatus', '<', 4)->orderBy('dateRequested', 'desc')->get();
            $closedRequests = $user->requestForms()->where('approvalStatus', '>', 3)->orderBy('dateRequested', 'desc')->paginate((new AppController())->paginate);
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
            'activeRequests' => RequestFormResource::collection($activeRequests),
            'closedRequests' => RequestFormResource::collection($closedRequests),
        ];

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json($response);
        else {
            //Web Response
            return Inertia::render('Index', $response);
        }
    }

    public function create(Request $request)
    {
        $projects = Project::orderBy('name', 'asc')->where('verified', 1)->get();
        $vehicles = Vehicle::orderBy('vehicleRegistrationNumber', 'asc')->where('verified', 1)->get();
        return Inertia::render('RequestForms/Create', [
            'projects' => ProjectResource::collection($projects),
            'vehicles' => VehicleResource::collection($vehicles),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);
        $stagesApprovalPosition = null;

        // Check the type of request
        if ($request->type == "PETTY_CASH" || $request->type == "REQUISITION") {

            //Validate all the important attributes
            $request->validate([
                'information' => ['required'],
                'total' => ['required'],
            ]);

            //get stages
            if (is_object($user->position)) {
                $stages = json_decode($user->position->approvalStages);
            } else
                return response()->json(['message' => "User position unknown"], 404);

            $stagesCount = count($stages);
            if ($stagesCount > 0) {
                $stagesApprovalPosition = $stages[0]->position;
            }

            $requestForm = RequestForm::create([
                'code' => (new AppController())->generateUniqueCode("REQUESTFORM"),
                'code_alt' => $this->getCodeRequestFormNumber(),
                //Requested information
                'type' => $request->type,
                'personCollectingAdvance' => $request->personCollectingAdvance,
                'purpose' => $request->purpose,
//                'project_id'                    =>  $request->projectId,
                'information' => json_encode($request->information),
                'total' => $request->total,

                //Requested by
                'user_id' => $user->id,
                'dateRequested' => Carbon::now()->getTimestamp(),

                //Stages
                'stagesApprovalPosition' => $stagesApprovalPosition,
                'stagesApprovalStatus' => $stagesCount == 0,
                'currentStage' => $stagesCount == 0 ? null : 1,
                'totalStages' => $stagesCount == 0 ? null : $stagesCount,
                'stages' => json_encode($stages),
                'quotes' => json_encode($request->quotes ?? []),
                'remarks' => json_encode([]),
                'receipts' => json_encode([]),

                //Management Approval
                'approvalStatus' => 0,
                'editable' => true,
            ]);

            //Run notifications
            (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");

        } elseif ($request->type == "VEHICLE_MAINTENANCE") {

            //Validate all the important attributes
            $request->validate([
                'information' => ['required'],
                'total' => ['required'],
                'vehicleId' => ['required'],
            ]);

            //get stages
            if (is_object($user->position)) {
                $stages = json_decode($user->position->approvalStages);
            } else
                return response()->json(['message' => "User position unknown"], 404);

            $stagesCount = count($stages);
            if ($stagesCount > 0) {
                $stagesApprovalPosition = $stages[0]->position;
            }

            //get vehicle details
            $vehicle = Vehicle::find($request->vehicleId);

            if (!is_object($vehicle))
                return response()->json(['message' => "Vehicle unknown"], 404);


            $requestForm = RequestForm::create([
                'code' => (new AppController())->generateUniqueCode("REQUESTFORM"),
                'code_alt' => $this->getCodeRequestFormNumber(),
                //Requested information
                'type' => $request->type,
                'assessedBy' => $request->assessedBy,
                'vehicle_id' => $request->vehicleId,
                'information' => json_encode($request->information),
                'total' => $request->total,

                //Requested by
                'user_id' => $user->id,
                'dateRequested' => Carbon::now()->getTimestamp(),

                //Stages
                'stagesApprovalPosition' => $stagesApprovalPosition,
                'stagesApprovalStatus' => $stagesCount == 0,
                'currentStage' => $stagesCount == 0 ? null : 1,
                'totalStages' => $stagesCount == 0 ? null : $stagesCount,
                'stages' => json_encode($stages),
                'quotes' => json_encode($request->quotes ?? []),
                'remarks' => json_encode([]),
                'receipts' => json_encode([]),

                //Management Approval
                'approvalStatus' => 0,
                'editable' => true,
            ]);

            //Run notifications
            (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");

        } elseif ($request->type == "FUEL") {

            //Validate all the important attributes
            $request->validate([
                'vehicleId' => ['required'],
                'driverName' => ['required'],
                'fuelVehicleMileage' => ['required'],
                'fuelRequestedLitres' => ['required'],
                'fuelRequestedMoney' => ['required'],
                'purpose' => ['required'],
            ]);

            //get stages
            if (is_object($user->position)) {
                $stages = json_decode($user->position->approvalStages);
            } else
                return response()->json(['message' => "User position unknown"], 404);

            $stagesCount = count($stages);
            if ($stagesCount > 0) {
                $stagesApprovalPosition = $stages[0]->position;
            }


            //get vehicle details
            $vehicle = Vehicle::find($request->vehicleId);

            if (!is_object($vehicle))
                return response()->json(['message' => "Vehicle unknown"], 404);


            $requestForm = RequestForm::create([
                'code' => (new AppController())->generateUniqueCode("REQUESTFORM"),
                'code_alt' => $this->getCodeRequestFormNumber(),
                //Requested information
                'type' => $request->type,
                'driverName' => $request->driverName,
                'fuelRequestedLitres' => $request->fuelRequestedLitres,
                'fuelRequestedMoney' => $request->fuelRequestedMoney,
                'purpose' => $request->purpose,
                'project_id' => $request->projectId,

                //Vehicle Details
                'vehicle_id' => $request->vehicleId,
                'mileage' => $request->fuelVehicleMileage,
                'lastRefillDate' => $request->lastRefillDate,
                'lastRefillFuelReceived' => $request->lastRefillFuelReceived,
                'lastRefillMileageCovered' => $request->lastRefillMileageCovered,

                //Requested by
                'user_id' => $user->id,
                'dateRequested' => Carbon::now()->getTimestamp(),

                //Stages
                'stagesApprovalPosition' => $stagesApprovalPosition,
                'stagesApprovalStatus' => $stagesCount == 0,
                'currentStage' => $stagesCount == 0 ? null : 1,
                'totalStages' => $stagesCount == 0 ? null : $stagesCount,
                'stages' => json_encode($stages),
                'quotes' => json_encode($request->quotes ?? []),
                'remarks' => json_encode([]),
                'receipts' => json_encode([]),

                //Management Approval
                'approvalStatus' => 0,
                'editable' => true,
            ]);

            //Run notifications
            (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form type unknown"], 422);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form type unknown');
            }
        }

        $report = (new ReportController())->getCurrentReport();
        $report->requestForms()->attach($requestForm);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new RequestFormResource($requestForm), 201);
        else {
            //Web Response
            return Redirect::route('dashboard')->with('success', 'Request created!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function storeFromDelivery(Request $request, $summary_id)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);
        $stagesApprovalPosition = null;

        $summary = Summary::find($summary_id);
        if (is_object($summary)) {

            //Validate all the important attributes
            $request->validate([
                'expenses' => ['required'],
            ]);

            //get stages
            if (is_object($user->position)) {
                $stages = json_decode($user->position->approvalStages);
            } else
                return response()->json(['message' => "User position unknown"], 404);

            $stagesCount = count($stages);
            if ($stagesCount > 0) {
                $stagesApprovalPosition = $stages[0]->position;
            }

            $information = [];
            $total = 0;
            if ($request->expenses["transportation"]["check"]) {
                $information [] = [
                    "details" => 'Transportation',
                    "units" => 'Unit',
                    "quantity" => 1,
                    "unitCost" => $request->expenses["transportation"]["amount"],
                    "totalCost" => $request->expenses["transportation"]["amount"],
                    "expenseTypeId" => env('OPERATIONS_EXPENSE_TYPE_ID'),
                    "transporterId" => $request->expenses["transportation"]["transporterId"],
                ];
                $total += $request->expenses["transportation"]["amount"];
            }
            if ($request->expenses["product"]["check"]) {
                $information [] = [
                    "details" => "Product Cost ({$summary->description})",
                    "units" => '',
                    "quantity" => 1,
                    "unitCost" => $request->expenses["product"]["amount"],
                    "totalCost" => $request->expenses["product"]["amount"],
                    "expenseTypeId" => env('OPERATIONS_EXPENSE_TYPE_ID'),
                    "supplierId" => $request->expenses["product"]["supplierId"],
                ];
                $total += $request->expenses["product"]["amount"];
            }
            if ($request->expenses["other"]["check"]) {
                $information [] = [
                    "details" => $request->expenses["other"]["description"],
                    "units" => '',
                    "quantity" => 1,
                    "unitCost" => $request->expenses["other"]["amount"],
                    "totalCost" => $request->expenses["other"]["amount"],
                    "comments" => $request->expenses["other"]["comments"],
                    "expenseTypeId" => env('OPERATIONS_EXPENSE_TYPE_ID'),

                ];
                $total += $request->expenses["other"]["amount"];
            }

            $requestForm = RequestForm::create([
                'code' => (new AppController())->generateUniqueCode("REQUESTFORM"),
                'code_alt' => $this->getCodeRequestFormNumber(),
                //Requested information
                'type' => "REQUISITION",
                'personCollectingAdvance' => $request->personCollectingAdvance,
                'purpose' => "Costs under SALE ORDER: #LL{$summary->sale->formattedCode()}",
//                'project_id'                    =>  $request->projectId,
                'information' => json_encode($information),
                'total' => $total,

                'delivery_id' => $summary->delivery->id,

                //Requested by
                'user_id' => $user->id,
                'dateRequested' => Carbon::now()->getTimestamp(),

                //Stages
                'stagesApprovalPosition' => $stagesApprovalPosition,
                'stagesApprovalStatus' => $stagesCount == 0,
                'currentStage' => $stagesCount == 0 ? null : 1,
                'totalStages' => $stagesCount == 0 ? null : $stagesCount,
                'stages' => json_encode($stages),
                'quotes' => json_encode($request->quotes ?? []),
                'remarks' => json_encode([]),
                'receipts' => json_encode([]),

                //Management Approval
                'approvalStatus' => 0,
                'editable' => true,
            ]);

            //Run notifications
            (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");

            $report = (new ReportController())->getCurrentReport();
            $report->requestForms()->attach($requestForm);

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new RequestFormResource($requestForm), 201);
            else {
                //Web Response
                return Redirect::route('dashboard')->with('success', 'Request created!');
            }
        } else {
            return Redirect::back()->with('error', 'Delivery not found');
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function storeFromPayable(Request $request)
    {
        //get user
        $user = (new AppController())->getAuthUser($request);
        $stagesApprovalPosition = null;


        //Validate all the important attributes
        $request->validate([
            'payables' => ['required'],
            'total' => ['required'],
        ]);

        //get stages
        if (is_object($user->position)) {
            $stages = json_decode($user->position->approvalStages);
        } else
            return response()->json(['message' => "User position unknown"], 404);

        $stagesCount = count($stages);
        if ($stagesCount > 0) {
            $stagesApprovalPosition = $stages[0]->position;
        }

        $information = [];
        $total = 0;
        foreach ($request->payables as $payable) {

            $information [] = [
                "details" => $payable["description"],
                "units" => 'Unit',
                "quantity" => 1,
                "unitCost" => $payable["total"],
                "totalCost" => $payable["total"],
                "expenseTypeId" => env('OPERATIONS_EXPENSE_TYPE_ID'),
                "transporterId" => $payable["transporter"] != null ? $payable["transporter"]["id"] : null,
                "supplierId" => $payable["supplier"] != null ? $payable["supplier"]["id"] : null,
            ];
        }

        $requestForm = RequestForm::create([
            'code' => (new AppController())->generateUniqueCode("REQUESTFORM"),
            'code_alt' => $this->getCodeRequestFormNumber(),
            //Requested information
            'type' => "REQUISITION",
            'personCollectingAdvance' => $request->personCollectingAdvance,
            'purpose' => "Payables for {$request->payables[0]["description"]}",
            'information' => json_encode($information),
            'total' => $request->total,

            'delivery_id' => null,

            //Requested by
            'user_id' => $user->id,
            'dateRequested' => Carbon::now()->getTimestamp(),

            //Stages
            'stagesApprovalPosition' => $stagesApprovalPosition,
            'stagesApprovalStatus' => $stagesCount == 0,
            'currentStage' => $stagesCount == 0 ? null : 1,
            'totalStages' => $stagesCount == 0 ? null : $stagesCount,
            'stages' => json_encode($stages),
            'quotes' => json_encode($request->quotes ?? []),
            'remarks' => json_encode([]),
            'receipts' => json_encode([]),

            //Management Approval
            'approvalStatus' => 0,
            'editable' => true,
        ]);

        //attach requisition to payables
        foreach ($request->payables as $payable) {
            $_payable = Payable::find($payable["id"]);
            $_payable->update([
                "request_id" => $requestForm->id
            ]);
        }


        //Run notifications
        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");

        $report = (new ReportController())->getCurrentReport();
        $report->requestForms()->attach($requestForm);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new RequestFormResource($requestForm), 201);
        else {
            //Web Response
            return Redirect::route('dashboard')->with('success', 'Request created!');
        }


    }


    private function getCodeRequestFormNumber()
    {
        $last = RequestForm::orderBy("code_alt", "desc")->first();
        if (is_object($last)) {
            return $last->code_alt + 1;
        } else {
            return 1;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function approve(Request $request, int $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {

            //get user
            $user = (new AppController())->getAuthUser($request);

            //check if request can be approved, if it is in pending state
            if ($requestForm->approvalStatus == 0) {
                //check if the user is the owner of the request
                if ($requestForm->user->id == $user->id) {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "You cannot approve your own request form"], 405);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'You cannot approve your own request form');
                    }
                }


                //check whether it needs a stage or management approval

                //it is a management approval
                if ($requestForm->stagesApprovalStatus) {

                    //check if user has management status
                    if ($user->hasRole('management')) {

                        //approve request
                        $requestForm->update([
                            'approvalStatus' => 1,
                            'approval_by_id' => $user->id,
                            'approvedDate' => Carbon::now()->getTimestamp(),
                            'remarks' => $this->addRemarks($user, $requestForm, $request->remarks),
                            'editable' => false,
                            'denied_by_id' => null,
                        ]);

                        //attach this request under approved requests
                        $requestForm->approvedBy()->attach($user);

                        (new NotificationController())->notifyApproval($requestForm, $user);
                        (new NotificationController())->notifyFinance($requestForm, "WAITING_INITIATE");

                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(new RequestFormResource($requestForm));
                        } else {
                            //Web Response
                            return Redirect::back()->with('success', 'Request approved');
                        }

                    } else {
                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(['message' => 'Unauthorized. Does not have access rights.'], 403);
                        } else {
                            //Web Response
                            return Redirect::back()->with('error', 'Unauthorized. Does not have access rights.');
                        }
                    }
                } //it is a stage approval
                else {

                    //check if user is the one to approve the current stage
                    if ($requestForm->stagesApprovalPosition == $user->position->id) {

                        $stages = json_decode($requestForm->stages);
                        $currentStage = $requestForm->currentStage;
                        $stages[$currentStage - 1] = [
                            "stage" => $currentStage,
                            "position" => $user->position->id,
                            "positionTitle" => $user->position->title,
                            "userId" => $user->id,
                            "name" => $user->firstName . " " . $user->middleName . " " . $user->lastName,
                            "date" => Carbon::now()->getTimestamp(),
                            "status" => true
                        ];
                        //move  to the next stage
                        $currentStage = $currentStage + 1;

                        //check if there are more stages
                        if ($currentStage <= $requestForm->totalStages) {

                            //upload the stages and get next position
                            $nextStage = $stages[$currentStage - 1]->position;

                            $requestForm->update([
                                'stages' => json_encode($stages),
                                'currentStage' => $currentStage,
                                'stagesApprovalPosition' => $nextStage,
                                'remarks' => $this->addRemarks($user, $requestForm, $request->remarks),
                                'editable' => false,
                                'denied_by_id' => null,
                            ]);

                        } //there are no more stages
                        else {

                            //close the stages approval section
                            $requestForm->update([
                                'stages' => json_encode($stages),
                                'stagesApprovalPosition' => null,
                                'stagesApprovalStatus' => true,
                                'remarks' => $this->addRemarks($user, $requestForm, $request->remarks),
                                'editable' => false,
                                'denied_by_id' => null,
                            ]);
                        }

                        //attach this request under approved requests
                        $requestForm->approvedBy()->attach($user);

                        //Run notifications
                        (new NotificationController())->notifyApproval($requestForm, $user);
                        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");

                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(new RequestFormResource($requestForm));
                        } else {
                            //Web Response
                            return Redirect::back()->with('success', 'Request approved');
                        }

                    } else {
                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(['message' => 'Unauthorized. Not the next to approve this request.'], 403);
                        } else {
                            //Web Response
                            return Redirect::back()->with('error', 'Unauthorized. Not the next to approve this request.');
                        }
                    }
                }

            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request cannot be approved"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request cannot be approved');
                }
            }

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form not found');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function deny(Request $request, int $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {

            // Remarks for denying the request are a must
            $request->validate([
                'remarks' => 'required'
            ]);

            //get user
            $user = (new AppController())->getAuthUser($request);

            //check if request can be approved, if it is in pending state
            if ($requestForm->approvalStatus == 0) {

                //it is a management approval
                if ($requestForm->stagesApprovalStatus) {

                    //check if user has management status
                    if ($user->hasRole('management')) {

                        //deny request
                        $requestForm->update([
                            'approvalStatus' => 2,
                            'remarks' => $this->addRemarks($user, $requestForm, $request->remarks),
                            'denied_by_id' => $user->id,
                            'editable' => true,
                        ]);

                        //Run Notifications
                        (new NotificationController())->notifyDenial($requestForm, $user);

                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(new RequestFormResource($requestForm));
                        } else {
                            //Web Response
                            return Redirect::back()->with('success', 'Request denied');
                        }

                    } else {
                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(['message' => 'Unauthorized. Does not have access rights.'], 403);
                        } else {
                            //Web Response
                            return Redirect::back()->with('error', 'Unauthorized. Does not have access rights.');
                        }
                    }
                } //it is a stage approval
                else {

                    //check if user is the one to approve the current stage
                    if ($requestForm->stagesApprovalPosition == $user->position->id) {

                        //deny request
                        $requestForm->update([
                            'approvalStatus' => 2,
                            'remarks' => $this->addRemarks($user, $requestForm, $request->remarks),
                            'denied_by_id' => $user->id,
                            'editable' => true
                        ]);

                        //Run Notifications
                        (new NotificationController())->notifyDenial($requestForm, $user);

                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(new RequestFormResource($requestForm));
                        } else {
                            //Web Response
                            return Redirect::back()->with('success', 'Request denied');
                        }

                    } else {
                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(['message' => 'Unauthorized. Not the next to attend to this request.'], 403);
                        } else {
                            //Web Response
                            return Redirect::back()->with('error', 'Unauthorized. Not the next to attend to this request.');
                        }
                    }
                }

            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request cannot be denied"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request cannot be denied');
                }
            }

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form not found');
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);
        $expenseTypes = ExpenseType::orderBy("name", "asc")->get();
        $accounts = Account::all();

        if (is_object($requestForm)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new RequestFormResource($requestForm));
            } else {
                //Web Response
                return Inertia::render('RequestForms/Show', [
                    'request' => new RequestFormResource($requestForm),
                    'expenseTypes' => $expenseTypes,
                    'accounts' => $accounts,
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Request form not found');
            }
        }
    }

    public function edit(Request $request, $id)
    {
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {
            //get user
            $user = (new AppController())->getAuthUser($request);

            //check if the user is the owner of the request
            if ($requestForm->user->id != $user->id) {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "You are not the owner of this request form"], 405);
                } else {
                    //Web Response
                    return Redirect::route('dashboard')->with('error', 'You are not the owner of this request form');
                }
            }


            $projects = Project::orderBy('name', 'asc')->where('verified', 1)->get();
            $vehicles = Vehicle::orderBy('vehicleRegistrationNumber', 'asc')->where('verified', 1)->get();
            return Inertia::render('RequestForms/Edit', [
                'projects' => ProjectResource::collection($projects),
                'vehicles' => VehicleResource::collection($vehicles),
                'request' => new RequestFormResource($requestForm)
            ]);
        } else {
            return Redirect::back()->with('error', 'Request form not found');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {

            //get user
            $user = (new AppController())->getAuthUser($request);

            //adjust editable
            $approvedByUsers = $requestForm->approvedBy;

            //check if request can be approved, if it is in pending state
            if ($requestForm->editable) {

                //check if the user is the owner of the request
                if ($requestForm->user->id != $user->id) {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "You are not the owner of this request form"], 405);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'You are not the owner of this request form');
                    }
                }

                // Check the type of request
                if ($request->type == "PETTY_CASH" || $request->type == "REQUISITION") {

                    //Validate all the important attributes
                    $request->validate([
                        'information' => ['required'],
                        'total' => ['required'],
                    ]);


                    $requestForm->update([
                        'personCollectingAdvance' => $request->personCollectingAdvance,
                        'purpose' => $request->purpose,
//                        'project_id'                    =>  $request->projectId,
                        'information' => json_encode($request->information),
                        'total' => $request->total,
                        'quotes' => json_encode($request->quotes ?? []),
                        'approvalStatus' => 0,
                        'editable' => $approvedByUsers->isEmpty(),
                    ]);


                } elseif ($requestForm->type == "VEHICLE_MAINTENANCE") {

                    //Validate all the important attributes
                    $request->validate([
                        'information' => ['required'],
                        'total' => ['required'],
                        'vehicleId' => ['required'],
                    ]);

                    //get vehicle details
                    $vehicle = Vehicle::find($request->vehicleId);

                    if (!is_object($vehicle))
                        return response()->json(['message' => "Vehicle unknown"], 404);


                    $requestForm->update([
                        'assessedBy' => $request->assessedBy,
                        'vehicle_id' => $request->vehicleId,
                        'information' => json_encode($request->information),
                        'total' => $request->total,
                        'quotes' => json_encode($request->quotes ?? []),
                        'approvalStatus' => 0,
                        'editable' => $approvedByUsers->isEmpty(),
                    ]);

                } elseif ($requestForm->type == "FUEL") {

                    //Validate all the important attributes
                    $request->validate([
                        'vehicleId' => ['required'],
                        'driverName' => ['required'],
                        'fuelVehicleMileage' => ['required'],
                        'fuelRequestedLitres' => ['required'],
                        'fuelRequestedMoney' => ['required'],
                        'purpose' => ['required'],
                    ]);

                    //get vehicle details
                    $vehicle = Vehicle::find($request->vehicleId);

                    if (!is_object($vehicle))
                        return response()->json(['message' => "Vehicle unknown"], 404);


                    $requestForm->update([
                        'driverName' => $request->driverName,
                        'fuelRequestedLitres' => $request->fuelRequestedLitres,
                        'fuelRequestedMoney' => $request->fuelRequestedMoney,
                        'purpose' => $request->purpose,
                        'project_id' => $request->projectId,
                        'vehicle_id' => $request->vehicleId,
                        'mileage' => $request->fuelVehicleMileage,
                        'lastRefillDate' => $request->lastRefillDate,
                        'lastRefillFuelReceived' => $request->lastRefillFuelReceived,
                        'lastRefillMileageCovered' => $request->lastRefillMileageCovered,
                        'quotes' => json_encode($request->quotes ?? []),
                        'approvalStatus' => 0,
                        'editable' => $approvedByUsers->isEmpty(),
                    ]);

                } else {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "Request form type unknown"], 422);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'Request form type unknown');
                    }
                }
            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request cannot be edited"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request cannot be edited');
                }
            }

        } else {
            {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request form not found"], 404);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request form not found');
                }
            }
        }

        //Run Notifications
        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_RESUBMITTED");

        if ((new AppController())->isApi($request)) {
            //API Response
            return response()->json(new RequestFormResource($requestForm));
        } else {
            //Web Response
            return Redirect::route('request-forms.show', ['id' => $requestForm->id])->with('success', 'Request edited and resubmitted');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(Request $request, $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {
            /*
                        For force delete
                        $quotes=json_decode($request->quotes);
                        if (count($quotes)>0){
                            foreach ($quotes as $quote){
                                if(file_exists($quote))
                                    Storage::disk("public_uploads")->delete($quote);
                            }
                        }
            */

            $requestForm->delete();

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => 'Request has been deleted']);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('success', 'Request has been deleted');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form not found');
            }
        }
    }

    public function appendRemarks(Request $request, $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {

            // Remarks for denying the request are a must
            $request->validate([
                'remarks' => 'required'
            ]);

            //get user
            $user = (new AppController())->getAuthUser($request);

            //check if request is editable
            if ($requestForm->editable) {
                //check if request can be approved, if it is in pending state
                if ($requestForm->approvalStatus == 0 || $requestForm->approvalStatus == 2) {

                    //check if the user is the owner of the request
                    if ($requestForm->user->id != $user->id) {
                        if ((new AppController())->isApi($request)) {
                            //API Response
                            return response()->json(['message' => "You are not the owner of this request form"], 405);
                        } else {
                            //Web Response
                            return Redirect::back()->with('error', 'You are not the owner of this request form');
                        }
                    }

                    $requestForm->update([
                        'remarks' => $this->addRemarks($user, $requestForm, $request->remarks),
                    ]);

                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(new RequestFormResource($requestForm));
                    } else {
                        //Web Response
                        return Redirect::back()->with('success', 'Remark added');
                    }

                } else {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "You cannot add a remark to this request"], 405);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'You cannot add a remark to this request');
                    }
                }
            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request cannot be edited"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request cannot be edited');
                }
            }

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form not found');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function discard(Request $request, $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {

            $requestForm->update([
                'approvalStatus' => 5,
            ]);

            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new RequestFormResource($requestForm));
            } else {
                //Web Response
                return Redirect::back()->with('success', 'Request has been discarded');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form not found');
            }
        }
    }

    private function addRemarks($user, $requestForm, $newRemarks)
    {

        if (isset($newRemarks)) {
            //Add remarks
            $remarks = json_decode($requestForm->remarks);

            $remarks[] = [
                "positionTitle" => $user->position->title,
                "name" => $user->firstName . " " . $user->lastName,
                'comments' => $newRemarks,
                'date' => Carbon::now()->getTimestamp(),
            ];

            return json_encode($remarks);
        } else
            return $requestForm->remarks;
    }

    public function initiate(Request $request, $id)
    {
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {
            //check if the request form can be initiated
            if ($requestForm->approvalStatus == 1) {

                if ($requestForm->dateInitiated == null) {

                    $request->validate([
                        'information' => 'required',
                        'account_id' => 'required',
                        'reference' => 'required',
                        'from_to' => 'required',
                    ]);

                    //create expenses
                    foreach ($request->information as $info) {
                        $sale_id = null;
                        if ($requestForm->delivery_id != null) {
                            $sale_id = $requestForm->delivery->summary->sale->id;
                        }

                        $expense = Expense::create([
                            "code" => (new ExpenseController())->getCodeNumber(),
                            "description" => $info["details"],
                            "total" => $info["totalCost"],
                            "date" => $info["date"],
                            "contents" => json_encode([
                                "comments" => $info["comments"]
                            ]),
                            "expense_type_id" => $info["expenseTypeId"],
                            "request_id" => $requestForm->id,
                            "transporter_id" => $info["transporterId"],
                            "supplier_id" => $info["supplierId"],
                            "delivery_id" => $requestForm->delivery_id,
                            "sale_id" => $sale_id,
                            "account_id" => $request->account_id,
                            "reference" => $request->reference,
                        ]);

                        $balance = $expense->account->balance - $expense->total;
                        $expense->account->update([
                            "balance" => $balance
                        ]);


                        Transaction::create([
                            "date" => $expense->date,
                            "reference" => strtoupper($expense->reference),
                            "description" => $expense->description,
                            "from_to" => $request->from_to,
                            "expense_id" => $expense->id,
                            "receipt_id" => null,
                            "account_id" => $expense->account->id,
                            "total" => $expense->total,
                            "balance" => $balance,
                            "type" => "DEBIT",
                        ]);

                    }

                    $requestForm->update([
                        //Should it be set manually?
                        //'dateInitiated' => $request->timestamp

                        'dateInitiated' => Carbon::now()->getTimestamp(),
                        'approvalStatus' => 3
                    ]);

                    foreach ($requestForm->payables as $payable) {
                        $payable->update([
                            "paid" => true
                        ]);
                    }

                    (new NotificationController())->notifyUser($requestForm, "INITIATED");
                    (new NotificationController())->notifyFinance($requestForm, "WAITING_RECONCILE");

                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(new RequestFormResource($requestForm));
                    } else {
                        //Web Response
                        return Redirect::back()->with('success', 'Request initiated');
                    }

                } else {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "Request is already initiated "], 405);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'Request is already initiated');
                    }
                }

            } elseif ($requestForm->approvalStatus == 0) {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request is still pending"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request is still pending');
                }

            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request cannot be initiated"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request cannot be initiated');
                }
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form not found');
            }
        }

    }

    public function reconcile(Request $request, $id)
    {
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {
            //check if the request form can be initiated
            if ($requestForm->approvalStatus == 3) {

                if ($requestForm->dateReconciled == null) {

                    /*   if($requestForm->type=="FUEL"){

                           $request->validate([
                               'lastRefillDate'            =>  ['required'],
                               'lastRefillFuelReceived'    =>  ['required','numeric'],
                               'lastRefillMileageCovered'  =>  ['required','numeric'],
                           ]);

                           $vehicle=Vehicle::find($requestForm->vehicle->id);

                           $mileage=$vehicle->mileage + $request->lastRefillMileageCovered;
                           $vehicle->update([
                               'mileage'                   =>  $mileage,
                               'lastRefillDate'            =>  $request->lastRefillDate,
                               'lastRefillFuelReceived'    =>  $request->lastRefillFuelReceived,
                               'lastRefillMileageCovered'  =>  $request->lastRefillMileageCovered,
                           ]);

                       }*/

                    $requestForm->update([
                        //Should it be set manually?
                        //'dateReconciled' => $request->timestamp
                        'dateReconciled' => Carbon::now()->getTimestamp(),
                        'approvalStatus' => 4,

                        //Should it be made compulsory?
                        'receipts' => json_encode($request->receipts ?? []),
                    ]);

                    (new NotificationController())->notifyUser($requestForm, "RECONCILED");

                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(new RequestFormResource($requestForm));
                    } else {
                        //Web Response
                        return Redirect::back()->with('success', 'Request reconciled');
                    }

                } else {
                    if ((new AppController())->isApi($request)) {
                        //API Response
                        return response()->json(['message' => "Request is already reconciled"], 405);
                    } else {
                        //Web Response
                        return Redirect::back()->with('error', 'Request is already reconciled');
                    }
                }

            } elseif ($requestForm->approvalStatus == 0) {

                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request is still pending"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request cannot be reconciled');
                }

            } else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Request cannot be reconciled"], 405);
                } else {
                    //Web Response
                    return Redirect::back()->with('error', 'Request cannot be reconciled');
                }
            }

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Request form not found');
            }
        }

    }

    public function findRequestForm(Request $request, $code)
    {
        //find out if the request is valid
        $requestForm = RequestForm::where('code', $code)->first();

        if (is_object($requestForm)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new RequestFormResource($requestForm));
            } else {
                //Web Response
                return Inertia::render('RequestForms/Show', [
                    'request' => new RequestFormResource($requestForm)
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Request form not found');
            }
        }
    }

    public function print(Request $request, $id)
    {
        //find out if the request is valid
        $requestForm = RequestForm::find($id);

        if (is_object($requestForm)) {

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $type = $this->getRequestTitle($requestForm->type);
            $filename = (new NotificationController())->getRequestTitle($requestForm->type, $requestForm->code) . " - " . date('Y F j');

            $now_d = Carbon::now('Africa/Lusaka')->format('jS F Y');
            $now_t = Carbon::now('Africa/Lusaka')->format('H:i');

            $pdf = PDF::loadView('request', [
                'type' => $type,
                'date' => $now_d,
                'time' => $now_t,
                'statusMessage' => $this->getStatusMessage($requestForm),
                'requestForm' => new RequestFormResource($requestForm)
            ]);
            return $pdf->download("$filename.pdf");

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Request form not found"], 404);
            } else {


                //Web Response
                return Redirect::route('dashboard')->with('error', 'Request form not found');
            }
        }
    }

    private function getRequestTitle($type)
    {
        switch ($type) {
            case "PETTY_CASH":
                return "Petty Cash Form";
            case "REQUISITION":
                return "Requisition Form";
            case "VEHICLE_MAINTENANCE":
                return "Vehicle Maintenance Request";
            default:
                return "Fuel Request";

        }
    }

    public function getStatusMessage($requestForm)
    {
        switch ($requestForm->approvalStatus) {
            case 0:
                if ($requestForm->stagesApprovalStatus)
                    return "Pending : Manager to approve";
                else {
                    $position = Position::find($requestForm->stagesApprovalPosition);
                    return "Pending : " . $position->title . " to approve";
                }
            case 3:
            case 1:
                return "Approved";
            case 2:
                return "Denied: By " . $requestForm->deniedBy->firstName . " " . $requestForm->deniedBy->middleName . " " . $requestForm->deniedBy->lastName;
            case 4:
                return "Reconciled";
            case 5:
                return "Discarded";
            default:
                return "Unknown";
        }
    }
}
