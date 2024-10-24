<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DeliveryVehicle extends Model implements HasMedia
{
    use HasUuids, InteractsWithMedia;

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
