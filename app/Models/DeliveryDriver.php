<?php

namespace App\Models;

use App\Enums\DeliveryDriverRegistrationStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class DeliveryDriver extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    protected $table = 'delivery_drivers';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = [
        'registration_status' => DeliveryDriverRegistrationStatus::class,
    ];

    protected $fillable = [
        'first_name',
        'surname',
        'email',
        'password',
        'landline_phone',
        'mobile_phone',
        'address_line_1',
        'town',
        'city',
        'county',
        'postcode',
        'registration_status',
        'status',
    ];

    public function vehicles()
    {
        return $this->hasMany(DeliveryVehicle::class);
    }
}
