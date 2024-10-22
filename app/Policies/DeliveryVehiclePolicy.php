<?php

namespace App\Policies;

class DeliveryVehiclePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        return auth()->check();
    }

    public function index()
    {
        return
            isUser(auth()->user()) ||
            isDeliveryDriver(auth()->user());
    }

    public function create($user)
    {
        return
            isUser(auth()->user()) ||
            isDeliveryDriver(auth()->user());
    }
}
