<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryDriverVehicle extends Model
{
    protected $table = 'delivery_driver_vehicles';

    public function driver()
    {
        return $this->belongsTo(DeliveryDriver::class);
    }

    public function location()
    {
        return $this->hasOne(DeliveryVehicleLocation::class);
    }
}
