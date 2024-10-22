<?php

use App\Models\Customer;
use App\Models\DeliveryDriver;
use App\Models\User;

if (! function_exists('isDeliveryDriver')) {
    function isDeliveryDriver($user)
    {
        return $user instanceof DeliveryDriver;
    }
}

if (! function_exists('isCustomer')) {
    function isCustomer($user)
    {
        return $user instanceof Customer;
    }
}

if (! function_exists('isUser')) {
    function isUser($user)
    {
        return $user instanceof User;
    }
}
