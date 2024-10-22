<?php

namespace App\Enums;

enum DeliveryVehicleType: string
{
    case BIKE = 'BIKE';
    case CAR = 'CAR';
    case ELECTRIC_BIKE = 'ELECTRIC_BIKE';
    case SCOOTER = 'SCOOTER';
}
