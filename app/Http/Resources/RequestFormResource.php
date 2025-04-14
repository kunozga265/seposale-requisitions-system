<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use App\Http\Controllers\RequestFormController;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //get user
        $user=(new AppController())->getAuthUser($request);
        $canEdit=$this->editable && $this->user->id == $user->id && $this->delivery == null;
        $canDelete=$this->editable && $this->user->id == $user->id && ($this->approvedBy->isEmpty()) && $this->deniedBy == null;
        $canDiscard=$this->editable && $this->user->id == $user->id && (!($this->approvedBy->isEmpty()) || $this->deniedBy != null);
        $canInitiate=$this->approvalStatus==1 && $user->hasRole('accountant');
        $canReconcile=$this->approvalStatus==3 && $user->hasRole('accountant');

        //next to approve
        if($this->stagesApprovalStatus == 0)
            $nextApprove=$this->stagesApprovalPosition == $user->position->id;
        else
            $nextApprove=$user->hasRole('management');


//        $canApproveOrDeny=$this->user->id != $user->id && $this->approvalBy == null && $this->approvalStatus!=2 && $nextApprove;
        $canApproveOrDeny=$this->approvalBy == null && $this->approvalStatus!=2 && $nextApprove;


        return [
            'id'                                  =>  $this->id,
            'code'                                =>  (new AppController())->getZeroedNumber($this->code_alt),
            'type'                                =>  $this->type,
            'personCollectingAdvance'             =>  $this->personCollectingAdvance,
            'purpose'                             =>  $this->purpose,
            'project'                             =>  new ProjectResource($this->project),
            'information'                         =>  json_decode($this->information),
            'total'                               =>  $this->total,
            'requestedBy'                         =>  new UserResource($this->user),
            'dateRequested'                       =>  $this->dateRequested,
            'nextPositionToApprove'              =>  $this->approvalPosition($this->stagesApprovalPosition),
            'stagesApprovalStatus'                =>  $this->stagesApprovalStatus,
            'currentStage'                        =>  $this->currentStage,
            'totalStages'                         =>  $this->totalStages,
            'stages'                              =>  json_decode($this->stages),
            'approvalStatus'                      =>  intval($this->approvalStatus),
            'approvedDate'                        =>  $this->approvedDate,
            'dateInitiated'                       =>  $this->dateInitiated,
            'dateReconciled'                      =>  $this->dateReconciled,
            'status'                              =>  $this->getApprovalStatus($this->approvalStatus),
            'statusMessage'                       =>  (new RequestFormController())->getStatusMessage($this),
            'approvedBy'                          =>  new UserResource($this->approvalBy),
            'deniedBy'                            =>  $this->deniedBy,
            'editable'                            =>  $this->editable,
            'remarks'                             =>  json_decode($this->remarks),
            'quotes'                              =>  json_decode($this->quotes),
            'receipts'                            =>  json_decode($this->receipts),
            'canEdit'                             =>  $canEdit,
            'canDelete'                           =>  $canDelete,
            'canDiscard'                          =>  $canDiscard,
            'canApproveOrDeny'                    =>  $canApproveOrDeny,
            'canInitiate'                         =>  $canInitiate,
            'canReconcile'                        =>  $canReconcile,
        ];
    }

    public function approvalPosition($positionId){
        $position=Position::find($positionId);

        if(is_object($position)){
            return [$position->title];
        }else{
            return ['Managing Director','Contracts Manager'];
        }
    }

    private function getApprovedBy($id){
        $user=User::find($id);
        if(is_object($user))
            return new UserResource($user);
        else
            return null;
    }

    private function getApprovalStatus($status){
        switch ($status){
            case 0:
                return "Pending";
            case 1:
                return "Approved";
            case 2:
                return "Denied";
            case 3:
                return "Initiated";
            case 4:
                return "Reconciled";
            case 5:
                return "Discarded";
            default:
                return "Unknown";
        }
    }


}
