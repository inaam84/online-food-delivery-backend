<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('index', Customer::class);

        return CustomerResource::collection(
            $this->customerRepository->getAllCustomers(array_filter($request->all()))
        );
    }

    public function profileShow()
    {
        Gate::authorize('profile', Customer::class);

        return response()->json(new CustomerResource(auth()->user()));
    }

    public function profileUpdate(CustomerUpdateRequest $request)
    {
        Gate::authorize('profile', Customer::class);

        $this->customerRepository->updateCustomer(auth()->user()->id, $request->validated());

        return jsonResponse(['message' => __('Profile has been updated successfully.')]);
    }

    public function show($id)
    {
        $customer = $this->customerRepository->getCustomerById($id);
        if (! $customer) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('show', $customer);

        return response()->json(new CustomerResource($customer));
    }

    public function update($id, CustomerUpdateRequest $request)
    {
        $customer = $this->customerRepository->getCustomerById($id);
        if (! $customer) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('update', $customer);

        $this->customerRepository->updateCustomer($customer->id, $request->validated());

        return jsonResponse(['message' => __('Profile has been updated successfully.')]);
    }
}
