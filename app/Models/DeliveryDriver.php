<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryDriver extends Model
{
    protected $table = 'delivery_drivers';

    public function vehicles()
    {
        return $this->hasMany(DeliveryDriverVehicle::class);
    }
}
