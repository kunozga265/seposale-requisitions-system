<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

     public function applications()
    {
        return $this->hasMany(Application::class);
    }


    protected $fillable = [
        "title",
        "slug",
        "date",
        "due_date",
        "department",
        "body",
        "fields",
    ];
}
