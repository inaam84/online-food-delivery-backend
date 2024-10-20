<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DeliveryDriver extends Model
{
    use HasUuids;

    protected $table = 'delivery_drivers';

    public function vehicles()
    {
        return $this->hasMany(DeliveryDriverVehicle::class);
    }
}
