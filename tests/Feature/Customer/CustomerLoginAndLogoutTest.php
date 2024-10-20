<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerLoginAndLogoutTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_logs_in_customer_and_sends_back_token(): void
    {
        $email = $this->faker->freeEmail;

        $customerData = [
            'first_name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'mobile_phone' => '07347584938',
            'address_line_1' => '11 Test Street',
            'postcode' => 'B90 8AJ',
        ];

        $registrationResponse = $this->postJson('/api/customer/register', $customerData);

        $registrationResponse->assertStatus(200);

        $customer = Customer::whereEmail($email)->first()->markEmailAsVerified();

        $loginResponse = $this->postJson('/api/customer/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200);

        $loginResponse->assertJsonStructure([
            'message',
            'token',
            'token_expires_at',
        ]);

        $loginResponse->assertJson([
            'message' => 'Login successful',
        ]);

        $this->assertNotEmpty($loginResponse->json('token'));
        $this->assertNotNull($loginResponse->json('token_expires_at'));
        $this->assertTrue(Carbon::parse($loginResponse->json('token_expires_at'))->greaterThan(now()));
    }

    /** @test */
    public function it_logs_out_customer(): void
    {
        $email = $this->faker->freeEmail;

        $customerData = [
            'first_name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'mobile_phone' => '07347584938',
            'address_line_1' => '11 Test Street',
            'postcode' => 'B90 8AJ',
        ];

        $registrationResponse = $this->postJson('/api/customer/register', $customerData);

        $registrationResponse->assertStatus(200);

        $customer = Customer::whereEmail($email)->first()->markEmailAsVerified();

        $loginResponse = $this->postJson('/api/customer/login', [
            'email' => $email,
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200);

        $loginResponse->assertJsonStructure([
            'message',
            'token',
            'token_expires_at',
        ]);

        $loginResponse->assertJson([
            'message' => 'Login successful',
        ]);

        $this->assertNotEmpty($loginResponse->json('token'));
        $this->assertNotNull($loginResponse->json('token_expires_at'));
        $this->assertTrue(Carbon::parse($loginResponse->json('token_expires_at'))->greaterThan(now()));

        $token = $loginResponse->json('token');

        $logoutResponse = $this->postJson('/api/customer/logout', [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $logoutResponse->assertJson([
            'message' => 'You are logged out successfully.',
        ]);
    }
}
