<?php

namespace App\Enums;

enum DeliveryDriverStatus: string
{
    case WORKING = 'WORKING';
    case NOT_WORKING = 'NOT_WORKING';
}
