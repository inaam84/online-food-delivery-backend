<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Vendor extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    protected $table = 'vendors';

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
        'business_name',
        'trading_name',
    ];

    public function scopeWithVerifiedEmail($query, $verified = true)
    {
        return $verified ? $query->whereNotNull('email_verified_at') : $query->whereNull('email_verified_at');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
