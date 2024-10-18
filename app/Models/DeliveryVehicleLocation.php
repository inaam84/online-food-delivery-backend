<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryVehicleLocation extends Model
{
    protected $table = 'delivery_vehicle_locations';

    public function vehicle()
    {
        return $this->belongsTo(DeliveryDriverVehicle::class);
    }
}
