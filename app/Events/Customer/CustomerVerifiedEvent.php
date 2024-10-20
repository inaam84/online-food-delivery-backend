<?php

namespace App\Events\Customer;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerVerifiedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Customer $customer;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}
