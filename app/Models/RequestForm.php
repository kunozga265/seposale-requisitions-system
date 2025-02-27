<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "requests";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsToMany(User::class,'requests_user','request_id','user_id');
    }

    public function deniedBy()
    {
        return $this->belongsTo(User::class,'denied_by_id','id');
    }

    public function approvalBy()
    {
        return $this->belongsTo(User::class,'approval_by_id','id');
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
        return $this->hasMany(Payable::class, "request_id","id");
    }

    public function expense()
    {
        return $this->hasOne(Expense::class, "request_id","id");
    }

    protected $fillable=[
        "code",
        "code_alt",
        "type",
        "personCollectingAdvance",
        "project_id",
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
