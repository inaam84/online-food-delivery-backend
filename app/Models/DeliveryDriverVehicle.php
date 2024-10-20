<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DeliveryDriverVehicle extends Model
{
    use HasUuids;
    
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
