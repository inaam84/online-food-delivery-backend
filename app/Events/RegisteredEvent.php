<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $entity;

    /**
     * Create a new event instance.
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
