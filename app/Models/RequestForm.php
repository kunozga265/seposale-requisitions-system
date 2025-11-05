<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "requests";

    public function user()
    {
        return $this->belongsTo(User::class,);
    }

    public function approvedBy()
    {
        return $this->belongsToMany(User::class, 'requests_user', 'request_id', 'user_id');
    }

    public function deniedBy()
    {
        return $this->belongsTo(User::class, 'denied_by_id', 'id');
    }

    public function approvalBy()
    {
        return $this->belongsTo(User::class, 'approval_by_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function payables()
    {
        return $this->hasMany(Payable::class, "request_id", "id");
    }

    public function expense()
    {
        return $this->hasOne(Expense::class, "request_id", "id");
    }


    public function items()
    {
        return $this->hasMany(RequestFormItem::class, "request_id", "id");
    }

    public function getName()
    {
        switch ($this->type) {
            case 'PETTY_CASH':
                return 'Petty Cash Request';
            case 'REQUISITION':
                return 'Requisition';
            case 'OPERATIONS':
                return 'Operations Request';
            case 'INVENTORY':
                return 'One Stop Shop Request';
            default:
                return  '';
        }
    }
    public function getFullName()
    {
        $code = (new AppController())->getZeroedNumber($this->code_alt);
        $type = '';
        switch ($this->type) {
            case 'PETTY_CASH':
                $type = 'Petty Cash Request';
                break;
            case 'REQUISITION':
                $type = 'Requisition';
                break;
            case 'OPERATIONS':
                $type = 'Operations Request';
                break;
            case 'INVENTORY':
                $type = 'One Stop Shop Request';
                break;
            default:
                $type =  '';
                break;
        }

        return "$type #$code";
    }

    public function formattedCode(){
        return (new AppController())->getZeroedNumber($this->code_alt);
    }

    protected $fillable = [
        "code",
        "code_alt",
        "type",
        "personCollectingAdvance",
        "project_id",
        "site_id",
        "information",
        "total",
        "delivery_id",
        "user_id",
        "dateRequested",
        "dateInitiated",
        "dateReconciled",
        "approval_by_id",
        "approvedDate",
        "approvalStatus",
        "stagesApprovalPosition",
        "stagesApprovalStatus",
        "currentStage",
        "totalStages",
        "stages",
        "assessedBy",
        "driverName",
        "fuelRequestedLitres",
        "fuelRequestedMoney",
        "purpose",
        "mileage",
        "lastRefillDate",
        "lastRefillFuelReceived",
        "lastRefillMileageCovered",
        "remarks",
        "vehicle_id",
        "quotes",
        "receipts",
        "editable",
        "denied_by_id"
    ];
}
