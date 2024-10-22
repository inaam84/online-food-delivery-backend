<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getAllCustomers(array $filters = [])
    {
        $page = 50;

        $query = Customer::query();
        if (array_key_exists('first_name', $filters)) {
            $query->where('first_name', 'LIKE', '%'.$filters['first_name'].'%');
        }
        if (array_key_exists('surname', $filters)) {
            $query->where('surname', 'LIKE', '%'.$filters['surname'].'%');
        }
        if (array_key_exists('email', $filters)) {
            $query->where('email', $filters['email']);
        }
        if (array_key_exists('page', $filters)) {
            $page = $filters['page'];
        }
        $page = $page > 50 ? 50 : $page;

        return $query->paginate($page);
    }

    public function getCustomerById($customerId)
    {
        return Customer::findOrFail($customerId);
    }

    public function deleteCustomer($customerId)
    {
        Customer::destroy($customerId);
    }

    public function createCustomer(array $customerDetails)
    {
        return Customer::create($customerDetails);
    }

    public function updateCustomer($customerId, array $newDetails)
    {
        return Customer::whereId($customerId)
            ->update($newDetails);
    }
}
