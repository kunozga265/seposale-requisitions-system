<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getName()
    {
        if($this->organisation != null && $this->alias != null){
            return "{$this->alias} ({$this->name})";
        }else{
            return $this->name;
        }
    }

    public function toRawResource()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            "phone_number" => $this->phone_number != null ? "+{$this->phone_number}" : null,
            "phone_number_other" => $this->phone_number_other != null ? "+{$this->phone_number_other}" : null,
            'email' => $this->email,
            'address' => $this->address,
            "organisation" => $this->organisation,
            "alias" => $this->alias
        ];
    }

    protected $fillable = [
        'serial',
        'name',
        'phone_number',
        'phone_number_other',
        'email',
        'address',
        "organisation",
        "alias"
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];
}
