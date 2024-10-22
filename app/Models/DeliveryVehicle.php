<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DeliveryVehicle extends Model
{
    use HasUuids;

    protected $table = 'delivery_driver_vehicles';

    protected $fillable = [
        'delivery_driver_id',
        'type',
        'registration_number',
        'year',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(DeliveryDriver::class);
    }

    public function location()
    {
        return $this->hasOne(DeliveryVehicleLocation::class);
    }
}
