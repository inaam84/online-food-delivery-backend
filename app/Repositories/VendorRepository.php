<?php

namespace App\Repositories;

use App\Interfaces\VendorRepositoryInterface;
use App\Models\Vendor;

class VendorRepository implements VendorRepositoryInterface
{
    public function getAllVendors(array $filters = [])
    {
        $page = 50;

        $query = Vendor::query();
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

    public function getVendorById($vendorId)
    {
        return Vendor::find($vendorId);
    }

    public function deleteVendor($vendorId)
    {
        Vendor::destroy($vendorId);
    }

    public function createVendor(array $vendorDetails)
    {
        return Vendor::create($vendorDetails);
    }

    public function updateVendor($vendorId, array $newDetails)
    {
        return Vendor::whereId($vendorId)
            ->update($newDetails);
    }
}
