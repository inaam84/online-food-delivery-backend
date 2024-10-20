<?php

namespace App\Listeners\Customer;

use App\Events\Customer\CustomerVerifiedEvent;

class CustomerVerifiedListener
{
    /**
     * Handle the event.
     *
     * @param  App\Events\Customer\CustomerVerifiedEvent  $event
     * @return void
     */
    public function handle(CustomerVerifiedEvent $event)
    {
        //
    }
}
