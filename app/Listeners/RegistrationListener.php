<?php

namespace App\Listeners;

use App\Events\RegisteredEvent;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class RegistrationListener
{
    /**
     * Handle the event.
     *
     * @param  App\Events\RegisteredEvent  $event
     * @return void
     */
    public function handle(RegisteredEvent $event)
    {
        if ($event->entity instanceof MustVerifyEmail && ! $event->entity->hasVerifiedEmail()) {
            $event->entity->notify(new VerifyEmailNotification($event->entity));
        }
    }
}
