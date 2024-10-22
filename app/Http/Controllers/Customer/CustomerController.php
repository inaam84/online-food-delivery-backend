<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        return CustomerResource::collection(
            $this->customerRepository->getAllCustomers(array_filter($request->all()))
        );
    }

    public function profile(Request $request)
    {
        // Retrieve the authenticated customer
        $customer = $request->user();

        if (! $customer) {
            return response()->json(['message' => 'Unauthenticated Customer.'], 401);
        }

        // Return customer profile data
        return response()->json(new CustomerResource($customer));
    }

    public function updateProfile(CustomerUpdateRequest $request)
    {
        // Retrieve the authenticated customer
        $customer = $request->user();

        if (! $customer) {
            return response()->json(['message' => 'Unauthenticated Customer.'], 401);
        }

        $this->customerRepository->updateCustomer($customer->id, $request->validated());

        return response()->json(['message' => 'Profile has been updated successfully.']);
    }
}
