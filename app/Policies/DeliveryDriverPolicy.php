<?php

namespace App\Policies;

use App\Models\DeliveryDriver;

class DeliveryDriverPolicy
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
        return isUser($loggedInUser);
    }

    public function show($loggedInUser, DeliveryDriver $driver)
    {
        if (! isDeliveryDriver($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isDeliveryDriver($loggedInUser) && $loggedInUser->id != $driver->id) {
            return false;
        }

        return true;
    }

    public function update($loggedInUser, DeliveryDriver $driver)
    {
        if (! isDeliveryDriver($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isDeliveryDriver($loggedInUser) && $loggedInUser->id != $driver->id) {
            return false;
        }

        return true;
    }

    public function uploadDocument($loggedInUser)
    {
        return isDeliveryDriver($loggedInUser) ||
            isUser($loggedInUser);
    }

    public function getDocumentsList($loggedInUser, DeliveryDriver $driver)
    {
        if (! isDeliveryDriver($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isDeliveryDriver($loggedInUser) && $loggedInUser->id != $driver->id) {
            return false;
        }

        return true;
    }

    public function downloadFile($loggedInUser)
    {
        return isDeliveryDriver($loggedInUser) ||
            isUser($loggedInUser);
    }

    public function profile($loggedInUser)
    {
        return isDeliveryDriver($loggedInUser);
    }
}
