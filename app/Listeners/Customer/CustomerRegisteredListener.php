<?php

namespace App\Listeners\Customer;

use App\Events\Customer\CustomerRegistered;
use App\Notifications\Customer\CustomerVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class CustomerRegisteredListener
{
    /**
     * Handle the event.
     *
     * @param  App\Events\Customer\CustomerRegistered  $event
     * @return void
     */
    public function handle(CustomerRegistered $event)
    {
        if ($event->customer instanceof MustVerifyEmail && ! $event->customer->hasVerifiedEmail()) {
            $event->customer->notify(new CustomerVerifyEmailNotification($event->customer));
        }
    }
}
