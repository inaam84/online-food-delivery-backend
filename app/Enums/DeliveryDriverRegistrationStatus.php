<?php

namespace App\Enums;

enum DeliveryDriverRegistrationStatus: string
{
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case REGISTERED = 'REGISTERED';
    case DISAPPROVED = 'DISAPPROVED';

    public static function asCommaSeparated(): string
    {
        return implode(',', array_column(self::cases(), 'value'));
    }
}
