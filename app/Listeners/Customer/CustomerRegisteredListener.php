<?php

namespace App\Listeners\Customer;

use App\Events\Customer\CustomerRegistered;
use App\Models\Customer;
use App\Notifications\Customer\CustomerVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
