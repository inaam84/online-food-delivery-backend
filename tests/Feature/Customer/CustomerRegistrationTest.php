<?php

namespace Tests\Feature\Customer;

use App\Events\RegisteredEvent;
use App\Models\Customer;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CustomerRegistrationTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_registers_a_customer_and_dispatches_event(): void
    {
        Event::fake(); // Mock the event dispatcher
        $email = $this->faker->freeEmail;

        $customerData = [
            'first_name' => 'John Doe',
            'surname' => 'John Doe',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'mobile_phone' => '07347584938',
            'address_line_1' => '11 Test Street',
            'postcode' => 'B90 8AJ',
        ];

        $response = $this->postJson('/api/customer/register', $customerData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('customers', [
            'email' => $email,
        ]);

        Event::assertDispatched(RegisteredEvent::class, function ($event) use ($customerData) {
            return $event->entity->email === $customerData['email'];
        });

        $response->assertJson([
            'message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.',
        ]);
    }

    /** @test */
    public function it_sends_an_email_verification_notification_to_customer(): void
    {
        Notification::fake(); // Mock the notification system
        $email = $this->faker->freeEmail;

        $customerData = [
            'first_name' => 'John Doe',
            'surname' => 'John Doe',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'mobile_phone' => '07347584938',
            'address_line_1' => '11 Test Street',
            'postcode' => 'B90 8AJ',
        ];

        $response = $this->postJson('/api/customer/register', $customerData);

        $response->assertStatus(200);

        $customer = Customer::where('email', $email)->first();

        Notification::assertSentTo($customer, VerifyEmailNotification::class);
    }
}
