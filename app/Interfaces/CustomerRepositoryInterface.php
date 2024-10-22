<?php

namespace App\Interfaces;

interface CustomerRepositoryInterface
{
    public function getAllCustomers(array $filters = []);

    public function getCustomerById($customerId);

    public function deleteCustomer($customerId);

    public function createCustomer(array $customerDetails);

    public function updateCustomer($customerId, array $newDetails);
}
