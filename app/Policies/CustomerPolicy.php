<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
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

    public function show($loggedInUser, Customer $customer)
    {
        if (! isCustomer($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isCustomer($loggedInUser) && $loggedInUser->id != $customer->id) {
            return false;
        }

        return true;
    }

    public function update($loggedInUser, Customer $customer)
    {
        if (! isCustomer($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isCustomer($loggedInUser) && $loggedInUser->id != $customer->id) {
            return false;
        }

        return true;
    }

    public function profile($loggedInUser)
    {
        return isCustomer($loggedInUser);
    }
}
