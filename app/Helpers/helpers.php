<?php

use App\Models\Customer;
use App\Models\DeliveryDriver;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Response;

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

if (! function_exists('isVendor')) {
    function isVendor($user)
    {
        return $user instanceof Vendor;
    }
}

if (! function_exists('jsonResponse')) {
    function jsonResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json([$data], $code);
    }
}
