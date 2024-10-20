<?php

namespace App\Notifications\Customer;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomerVerifyEmailNotification extends Notification
{
    use Queueable;

    public Customer $customer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
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
            ->action('Verify Email', $this->verificationUrl($this->customer))
            ->line('Thank you for using our application!');
    }

    protected function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'customer_verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $this->customer->getKey(), 'hash' => sha1($this->customer->getEmailForVerification())]
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
