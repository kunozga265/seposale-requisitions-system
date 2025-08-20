<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestFormResource;
use App\Models\RequestForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
     public function dashboard(Request $request)
    {
        //get user
        $user = User::find(Auth::id());

        $active = RequestForm::where('user_id', $user->id)->where('approvalStatus', '<', 4)->orderBy('dateRequested', 'desc')->get();
        $activeCount = $active->count();

        $awaitingInitiationCount = 0;
        $awaitingReconciliationCount = 0;

        //Contracts Manager
        if ($user->hasRole('management') && $user->hasRole('employee')) {
            $toApproveAsManager = RequestForm::where('approvalStatus', 0)->where('stagesApprovalStatus', 1)->where('user_id', '!=', $user->id)->orderBy('dateRequested', 'desc')->get();
            $toApproveAsEmployee = RequestForm::where('approvalStatus', 0)->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();
            $toApprove = $toApproveAsManager->merge($toApproveAsEmployee);

            $awaitingApprovalCount = $toApprove->count();

        } //Normal Manager
        else if ($user->hasRole('management')) {
            $toApprove = RequestForm::where('approvalStatus', 0)->where('stagesApprovalStatus', 1)->where('user_id', '!=', $user->id)->orderBy('dateRequested', 'desc')->get();
            $awaitingApprovalCount = $toApprove->count();

        } else
            if ($user->hasRole('accountant')) {

                $toReconcile = RequestForm::where('approvalStatus', 3)->where("dateRequested",">=",env('TIMESTAMP_CUTOFF'))->orderBy('dateRequested', 'desc')->get();
                $toInitiate = RequestForm::where('approvalStatus', 1)->where("dateRequested",">=",env('TIMESTAMP_CUTOFF'))->orderBy('dateRequested', 'desc')->get();
                $toApprove = RequestForm::where('approvalStatus', 0)->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();

                $awaitingApprovalCount = $toApprove->count();
                $awaitingInitiationCount = $toInitiate->count();
                $awaitingReconciliationCount = $toReconcile->count();

                //Merge
                $toApprove = $toApprove->merge($toInitiate);
                $toApprove = $toApprove->merge($toReconcile);

              
            } else {
                $toApprove = RequestForm::where('approvalStatus', 0)->where('stagesApprovalPosition', $user->position->id)->where('stagesApprovalStatus', 0)->orderBy('dateRequested', 'desc')->get();
                $awaitingApprovalCount = $toApprove->count();
            }

        $totalCount = $toApprove->count() + $active->count();

       return response()->json([
                'to_approve' => RequestFormResource::collection($toApprove),
                'active' => RequestFormResource::collection($active),
                //counts
                'awaiting_approval_count' => $awaitingApprovalCount,
                'awaiting_initiation_count' => $awaitingInitiationCount,
                'awaiting_reconciliation_count' => $awaitingReconciliationCount,
                'active_count' => $activeCount,
                'total_count' => $totalCount
            ]);
    }
}
