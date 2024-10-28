<?php

namespace App\Policies;

use App\Models\Vendor;

class VendorPolicy
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

    public function show($loggedInUser, Vendor $Vendor)
    {
        if (! isVendor($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isVendor($loggedInUser) && $loggedInUser->id != $Vendor->id) {
            return false;
        }

        return true;
    }

    public function update($loggedInUser, Vendor $Vendor)
    {
        if (! isVendor($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isVendor($loggedInUser) && $loggedInUser->id != $Vendor->id) {
            return false;
        }

        return true;
    }

    public function profile($loggedInUser)
    {
        return isVendor($loggedInUser);
    }
}
