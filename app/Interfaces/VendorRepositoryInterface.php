<?php

namespace App\Interfaces;

interface VendorRepositoryInterface
{
    public function getAllVendors();

    public function getVendorById($vendorId);

    public function deleteVendor($vendorId);

    public function createVendor(array $vendorDetails);

    public function updateVendor($vendorId, array $newDetails);
}
