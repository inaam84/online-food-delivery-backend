<?php

namespace App\Http\Controllers\Customer;

use App\Events\RegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRegistrationRequest;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    private CustomerRepositoryInterface $customerRepository;

    private AuthService $authService;

    public function __construct(CustomerRepositoryInterface $customerRepository, AuthService $authService)
    {
        $this->customerRepository = $customerRepository;
        $this->authService = $authService;
    }

    public function register(CustomerRegistrationRequest $request)
    {
        $customer = $this->customerRepository->createCustomer($request->validated());

        event(new RegisteredEvent($customer));

        return response()->json(['message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.']);
    }

    public function resendVerificationEmail(Request $request)
    {
        return $this->authService->resendVerificationEmail($request, Customer::class);
    }

    public function verify(Request $request, $id, $hash)
    {
        return $this->authService->verify($request, Customer::class, $id, $hash);
    }

    public function login(Request $request)
    {
        return $this->authService->login($request, Customer::class);
    }
}
