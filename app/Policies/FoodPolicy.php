<?php

namespace App\Policies;

use App\Models\Food;
use App\Models\User;

class FoodPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create($loggedInUser): bool
    {
        if (! isVendor($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($loggedInUser, Food $food): bool
    {
        if (! isVendor($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isVendor($loggedInUser) && $loggedInUser->id != $food->vendor_id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($loggedInUser, Food $food): bool
    {
        if (! isVendor($loggedInUser) && ! isUser($loggedInUser)) {
            return false;
        }

        if (isVendor($loggedInUser) && $loggedInUser->id != $food->vendor_id) {
            return false;
        }

        return true;
    }
}
