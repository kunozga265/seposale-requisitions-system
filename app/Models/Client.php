<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;

    public function totalPayments()
    {
        return $this->receipts()->sum('amount');
    }
    
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function siteSales()
    {
        return $this->hasMany(SiteSale::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
    public function type()
    {
        return $this->belongsTo(ClientType::class, "client_type_id");
    }

    public function getName()
    {
        if ($this->organisation != null && $this->alias != null) {
            return "{$this->alias} ({$this->name})";
        } else {
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

    public function hasAnyRole($roles)
    {
        return true;
    }

    protected $fillable = [
        'serial',
        'name',
        'password',
        'phone_number',
        'phone_number_other',
        'email',
        'address',
        "organisation",
        "alias",
        "client_type_id"
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
        "password",
    ];

    protected $casts = [
        'organisation' => 'boolean',
    ];


    /**
     * Generate and store OTP for this client
     */
    public function generateOtp(int $minutes = 5): string
    {
        $otp = (string) random_int(100000, 999999);

        Cache::put(
            $this->otpCacheKey(),
            $otp,
            now()->addMinutes($minutes)
        );

        return $otp;
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(string $otp): bool
    {
        $cacheKey = $this->otpCacheKey();

        if (! Cache::has($cacheKey)) {
            return false;
        }

        if (Cache::get($cacheKey) !== $otp) {
            return false;
        }

        // OTP is single-use
        Cache::forget($cacheKey);

        return true;
    }

    /**
     * OTP cache key
     */
    protected function otpCacheKey(): string
    {
        return 'client_otp_' . $this->id;
    }
}
