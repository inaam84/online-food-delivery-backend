<?php

namespace App\Repositories;

use App\Interfaces\DeliveryDriverRepositoryInterface;
use App\Models\DeliveryDriver;

class DeliveryDriverRepository implements DeliveryDriverRepositoryInterface
{
    public function getAllDrivers(array $filters = [])
    {
        $page = 50;

        $query = DeliveryDriver::query();
        if (array_key_exists('id', $filters)) {
            $query->where('id', $filters['id']);
        }
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

    public function getDriverById($driverId)
    {
        return DeliveryDriver::find($driverId);
    }

    public function deleteDriver($driverId)
    {
        DeliveryDriver::destroy($driverId);
    }

    public function createDriver(array $driverDetails)
    {
        return DeliveryDriver::create($driverDetails);
    }

    public function updateDriver($driverId, array $newDetails)
    {
        $driver = $this->getDriverById($driverId);

        return $driver->update($newDetails);
    }

    public function getDriverFile($driverId, $mediaId)
    {
        $driver = $this->getDriverById($driverId);

        return $driver->media()->where('uuid', $mediaId)->first();
    }
}
