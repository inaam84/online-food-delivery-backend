<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public $entity;

    /**
     * Create a new notification instance.
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email', $this->verificationUrl($this->entity))
            ->line('Thank you for using our application!');
    }

    protected function verificationUrl()
    {
        $route = '';
        if ($this->entity instanceof \App\Models\Customer) {
            $route = 'customer_verification.verify';
        } elseif ($this->entity instanceof \App\Models\DeliveryDriver) {
            $route = 'driver_verification.verify';
        } elseif ($this->entity instanceof \App\Models\User) {
            $route = 'user_verification.verify';
        }

        return URL::temporarySignedRoute(
            $route,
            Carbon::now()->addMinutes(60),
            ['id' => $this->entity->getKey(), 'hash' => sha1($this->entity->getEmailForVerification())]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
