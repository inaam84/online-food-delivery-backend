<?php

namespace Tests\Feature\DeliveryDriver;

use App\Enums\DeliveryDriverRegistrationStatus;
use App\Events\RegisteredEvent;
use App\Models\DeliveryDriver;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class DriverRegistrationTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_registers_a_driver_and_dispatches_event(): void
    {
        Event::fake(); // Mock the event dispatcher
        $email = $this->faker->freeEmail;

        $driverData = [
            'first_name' => 'John Doe',
            'surname' => 'John Doe',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'mobile_phone' => '07347584938',
            'address_line_1' => '11 Test Street',
            'postcode' => 'B90 8AJ',
            'county' => 'West Midlands',
        ];

        $response = $this->postJson('/api/delivery_drivers/register', $driverData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('delivery_drivers', [
            'email' => $email,
        ]);

        Event::assertDispatched(RegisteredEvent::class, function ($event) use ($driverData) {
            return $event->entity->email === $driverData['email'];
        });

        $response->assertJson([
            'message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.',
        ]);

        $driver = DeliveryDriver::where('email', $email)->first();
        $this->assertEquals(DeliveryDriverRegistrationStatus::PENDING_APPROVAL, $driver->registration_status);
    }

    /** @test */
    public function it_sends_an_email_verification_notification_to_driver(): void
    {
        Notification::fake(); // Mock the notification system
        $email = $this->faker->freeEmail;

        $driverData = [
            'first_name' => 'John Doe',
            'surname' => 'John Doe',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'mobile_phone' => '07347584938',
            'address_line_1' => '11 Test Street',
            'county' => 'Solihull',
            'postcode' => 'B90 8AJ',
        ];

        $response = $this->postJson('/api/delivery_drivers/register', $driverData);

        $response->assertStatus(200);

        $driver = DeliveryDriver::where('email', $email)->first();

        Notification::assertSentTo($driver, VerifyEmailNotification::class);
    }
}
