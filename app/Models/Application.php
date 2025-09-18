<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

      public function vacancy()
    {
        return $this->belongsTo(Vacancy::class, );
    }

    protected $fillable = [
        "first_name",
        "last_name",
        "date_of_birth",
        "email",
        "gender",
        "phone_number",
        "qualifications",
        "fields",
        "vacancy_id",
    ];
}
