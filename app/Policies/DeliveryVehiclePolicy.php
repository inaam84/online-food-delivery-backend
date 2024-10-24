<?php

namespace App\Policies;

use App\Models\DeliveryVehicle;

class DeliveryVehiclePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        return auth()->check();
    }

    public function index($loggedInUser)
    {
        return
            isUser($loggedInUser) ||
            isDeliveryDriver($loggedInUser);
    }

    public function create($loggedInUser)
    {
        return
            isUser($loggedInUser) ||
            isDeliveryDriver($loggedInUser);
    }

    public function update($loggedInUser, DeliveryVehicle $vehicle)
    {
        if (! isDeliveryDriver($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isDeliveryDriver($loggedInUser) && $loggedInUser->id != $vehicle->delivery_driver_id) {
            return false;
        }

        return true;
    }

    public function uploadDocument($loggedInUser, DeliveryVehicle $vehicle)
    {
        if (! isDeliveryDriver($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isDeliveryDriver($loggedInUser) && $loggedInUser->id != $vehicle->delivery_driver_id) {
            return false;
        }

        return true;
    }

    public function getDocumentsList($loggedInUser, DeliveryVehicle $vehicle)
    {
        if (! isDeliveryDriver($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isDeliveryDriver($loggedInUser) && $loggedInUser->id != $vehicle->delivery_driver_id) {
            return false;
        }

        return true;
    }

    public function downloadFile($loggedInUser)
    {
        if (! isDeliveryDriver($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        return true;
    }
}
