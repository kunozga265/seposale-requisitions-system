<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    public function deliveredBy()
    {
        return $this->belongsTo(User::class,"delivered_by");
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, "initiated_by");
    }

    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }

    public function logs()
    {
        return $this->hasMany(SystemLog::class);
    }
    public function requestForms()
    {
        return $this->hasMany(RequestForm::class);
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function overdue()
    {
        $due_date = Carbon::createFromTimestamp($this->due_date);
        $now = Carbon::now();
        $overdue = $now->diff($due_date);
        return $overdue->invert == 1;
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
    }

    protected $fillable = [
        "code",
        "serial",
        "status",
        "photo",
        "quantity_delivered",
        "tracking_number",
        "summary_id",
        "site_sale_summary_id",
        "due_date",
        "notes",
        "whatsapp",
    ];
}
