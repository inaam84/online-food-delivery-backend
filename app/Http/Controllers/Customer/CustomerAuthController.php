<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerRegisteredEvent;
use App\Events\Customer\CustomerVerifiedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRegistrationRequest;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use App\Notifications\Customer\CustomerVerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function register(CustomerRegistrationRequest $request)
    {
        $customer = $this->customerRepository->createCustomer($request->validated());

        event(new CustomerRegisteredEvent($customer));

        return response()->json(['message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.']);
    }

    public function resendVerificationEmail(Request $request)
    {
        // Validate the request to ensure email is provided
        $request->validate([
            'email' => 'required|email',
        ]);

        // Find the customer by email
        $customer = Customer::where('email', $request->email)->first();
        if (! $customer) {
            return response()->json(['message' => 'This email address is not registrated with us.'], 403);
        }

        // check if the customer is already verified
        if ($customer->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        // resend the verification email
        $customer->notify(new CustomerVerifyEmailNotification($customer));

        return response()->json(['message' => 'Verification email link sent successfully. It might take few minutes so please be patient.']);
    }

    public function verify(Request $request, $id, $hash)
    {
        $customer = Customer::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($customer->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        // if customer is already verified
        if ($customer->hasVerifiedEmail()) {
            return response()->json(['message' => 'Customer already verified']);
        }

        // Mark the customer as verified
        if ($customer->markEmailAsVerified()) {
            event(new CustomerVerifiedEvent($customer));
        }

        return response()->json(['message' => 'Customer successfully verified. Please use Login endpoint to login.']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if the customer exists and the password is correct
        $customer = Customer::where('email', $request->email)->first();
        if (! $customer || ! Hash::check($credentials['password'], $customer->password)) {
            throw ValidationException::withMessages([
                'message' => 'Invalid login credentials',
            ]);
        }

        // Check if the email is verified
        if (! $customer->hasVerifiedEmail()) {
            return response()->json(['message' => 'Please verify your email before logging in.'], 403);
        }

        // Delete any existing tokens for this customer
        $customer->tokens()->delete();

        // Generate a new Sanctum token
        $expiresAt = now()->addHours(config('sanctum.customer_expiration', 48));

        return response()->json([
            'message' => 'Login successful',
            'token' => $customer->createToken(config('app.name'), ['*'], $expiresAt)->plainTextToken,
            'token_expires_at' => $expiresAt,
        ]);
    }

    public function logout(Request $request)
    {
        $customer = $request->user();

        $customer->tokens()->delete();

        $customer->currentAccessToken()->delete();

        return response()->json('You are logged out successfully.');
    }
}
