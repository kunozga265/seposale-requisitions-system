<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Resources\API\RequestFormResource;
use App\Models\RequestForm;
use App\Models\AccountingAccount;
use App\Models\SUmmary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
            'approvedRequestsCount' => $approvedRequestsCount,
            'pendingRequestsCount' => $pendingRequestsCount,
            'deniedRequestsCount' => $deniedRequestsCount,
            'closedRequestsCount' => $closedRequestsCount,
            'requests' => RequestFormResource::collection($requests),

        ];

        return response()->json($response);
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


            //get stages
            if (is_object($user->position)) {
                $stages = json_decode($user->position->approvalStages);
            } else
                return response()->json(['message' => "User position unknown"], 404);

            $stagesCount = count($stages);
            if ($stagesCount > 0) {
                $stagesApprovalPosition = $stages[0]->position;
            }

            $items = [];
            $total = 0;
            if (isset($request->transportation)) {
                // $request->validate([
                //     'transporter_id' => ['required'],
                // ]);
                $transportation = $request->transportation;
                $transportation['accountId'] = $summary->product->inventory_account_id;
                // $transportation['transporterId'] = $request->transporter_id;
                $items[] = $transportation;

                $total += $transportation["totalCost"];
            }
            if (isset($request->supplier)) {

                // $request->validate([
                //     'supplier_id' => ['required'],
                // ]);
                $supplier = $request->supplier;
                $supplier['accountId'] = $summary->product->inventory_account_id;
                // $supplier['supplierId'] = $request->supplier_id;
                $items[] = $supplier;

                $total += $supplier["totalCost"];
            }
            if ($request->other) {
                $other = $request->other;
                $other['accountId'] = AccountingAccount::where("code", 6030)->first()->id; //Direct Expenses Account
                $other['comments'] = $request->comments;
                $items[] = $other;

                $total += $other["totalCost"];
            }

            $remarks = [];
            if (isset($request->remarks)) {
                $remarks[] = [
                    "positionTitle" => $user->position->title,
                    "name" => $user->firstName . " " . $user->lastName,
                    'comments' => $request->remarks,
                    'date' => Carbon::now()->getTimestamp(),
                ];
            }

            $requestForm = RequestForm::create([
                'code' => (new AppController())->generateUniqueCode("REQUESTFORM"),
                'code_alt' => $this->getCodeRequestFormNumber(),
                //Requested information
                'type' => "OPERATIONS",
                'personCollectingAdvance' => $request->personCollectingAdvance,
                'purpose' => "Costs under SALE ORDER: #LL{$summary->sale->formattedCode()}",
                //                'project_id'                    =>  $request->projectId,
                // 'information' => json_encode($information),
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
                'remarks' => json_encode($remarks),
                'receipts' => json_encode([]),

                //Management Approval
                'approvalStatus' => 0,
                'editable' => true,
            ]);

            //create request form items
            foreach ($items as $item) {
                $requestForm->items()->create([
                    'details' => $item['details'],
                    'units' => $item['units'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unitCost'],
                    'total_cost' => $item['totalCost'],
                    'balance' => $item['totalCost'],
                    'accounting_account_id' => $item['accountId'],
                    'transporter_id' => $item['transporterId'] ?? null,
                    'supplier_id' => $item['supplierId'] ?? null,
                    'status' => 0, //Pending,
                    'request_id' => $requestForm->id
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
        } else {
              if ((new AppController())->isApi($request))
                //API Response
             return response()->json(['message' => "Delivery not found"], 404);
            else {
                //Web Response
                return Redirect::back()->with('error', 'Delivery not found');
            }
        }
    }
}
