<?php

namespace App\Http\Controllers\Customer;

use App\Events\Customer\CustomerRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerRegistrationRequest;
use App\Interfaces\CustomerRepositoryInterface;

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

        event(new CustomerRegistered($customer));

        return response()->json(['message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.']);
    }
}
