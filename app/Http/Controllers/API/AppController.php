<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountingAccountResource;
use App\Http\Resources\API\RequestFormResource;
use App\Http\Resources\API\SiteResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ProductResource;
use App\Models\Account;
use App\Models\AccountingAccount;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Site;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\ProductVariant;
use App\Models\RequestForm;
use App\Models\Transporter;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Accounting;

class AppController extends Controller
{
    public $paginate = 20;

    public function dashboard(Request $request)
    {
        //get user
        $user = User::find(Auth::id());

        $active = RequestForm::where('user_id', $user->id)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->where('approvalStatus', '<', 4)->orderBy('dateRequested', 'desc')->get();
        $activeCount = $active->count();

        $awaitingInitiationCount = 0;
        $awaitingReconciliationCount = 0;

        //Contracts Manager
        if ($user->hasRole('management') && $user->hasRole('employee')) {
            $toApproveAsManager = RequestForm::where('approvalStatus', 0)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->where('stagesApprovalStatus', 1)->where('user_id', '!=', $user->id)->orderBy('dateRequested', 'desc')->get();
            $toApproveAsEmployee = RequestForm::where('approvalStatus', 0)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();
            $toApprove = $toApproveAsManager->merge($toApproveAsEmployee);

            $awaitingApprovalCount = $toApprove->count();
        } //Normal Manager
        else if ($user->hasRole('management')) {
            $toApprove = RequestForm::where('approvalStatus', 0)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->where('stagesApprovalStatus', 1)->where('user_id', '!=', $user->id)->orderBy('dateRequested', 'desc')->get();
            $awaitingApprovalCount = $toApprove->count();
        } else
            if ($user->hasRole('accountant')) {

            $toReconcile = RequestForm::where('approvalStatus', 3)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->orderBy('dateRequested', 'desc')->get();
            $toInitiate = RequestForm::where('approvalStatus', 1)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->orderBy('dateRequested', 'desc')->get();
            $toApprove = RequestForm::where('approvalStatus', 0)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();

            $awaitingApprovalCount = $toApprove->count();
            $awaitingInitiationCount = $toInitiate->count();
            $awaitingReconciliationCount = $toReconcile->count();

            //Merge
            $toApprove = $toApprove->merge($toInitiate);
            $toApprove = $toApprove->merge($toReconcile);
        } else {
            $toApprove = RequestForm::where('approvalStatus', 0)->where("dateRequested", ">=", env('TIMESTAMP_CUTOFF'))->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();
            $awaitingApprovalCount = $toApprove->count();
        }

        $totalCount = $toApprove->count() + $active->count();

        $clients = [];
        $products = [];
        $accounts = [];
        $transporters = [];
        $suppliers = [];

        //get latest/updated clients and products
        if ($request->query('timestamp') != null) {
            $date = Carbon::createFromTimestamp($request->query('timestamp'));

            $clients = Client::where("updated_at", ">=", $date)->get();
            $accounts = AccountingAccount::where("updated_at", ">=", $date)->get();
            $transporters = Transporter::where("updated_at", ">=", $date)->get();
            $suppliers = Supplier::where("updated_at", ">=", $date)->get();
            
            
            if(ProductVariant::where("updated_at", ">=", $date)->exists()){
                $products = Product::all();
            }

        }

        $sites = Site::orderBy("name","asc")->get();

        return response()->json([
            'to_approve' => RequestFormResource::collection($toApprove),
            'active' => RequestFormResource::collection($active),
            //counts
            'awaiting_approval_count' => $awaitingApprovalCount,
            'awaiting_initiation_count' => $awaitingInitiationCount,
            'awaiting_reconciliation_count' => $awaitingReconciliationCount,
            'active_count' => $activeCount,
            'total_count' => $totalCount,
            'products' => ProductResource::collection($products),
            'clients' => ClientResource::collection($clients),
            'accounts' => AccountingAccountResource::collection($accounts),
            'sites' => SiteResource::collection($sites),
            'transporters' => $transporters,
            'suppliers' => $suppliers,

        ]);
    }

    public function initialise(Request $request)
    {

        switch ($request->query('section')) {
            case "PRODUCTS":
                $products = Product::all();
                return response()->json(ProductResource::collection($products));
            case "CLIENTS":
                $clients = Client::paginate(200);
                return response()->json(ClientResource::collection($clients));
            case "ACCOUNTS":
                $accounts = AccountingAccount::paginate(200);
                return response()->json(AccountingAccountResource::collection($accounts));
            case "CLIENT_TYPES":
                $types = ClientType::all();
                return response()->json($types);
            case "PAYMENT_METHODS":
                $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
                return response()->json($payment_methods);
            case "TRANSPORTERS":
                $transporters = Transporter::orderBy("name", "asc")->get();
                return response()->json($transporters);
            case "SUPPLIERS":
                $suppliers = Supplier::orderBy("name", "asc")->get();
                return response()->json($suppliers);
            default:
                return response()->json([]);
        }
    }
}
