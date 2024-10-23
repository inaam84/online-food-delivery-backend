<?php

namespace App\Interfaces;

interface DeliveryDriverRepositoryInterface
{
    public function getAllDrivers();

    public function getDriverById($driverId);

    public function deleteDriver($driverId);

    public function createDriver(array $driverDetails);

    public function updateDriver($driverId, array $newDetails);

    public function getDriverFile($driverId, $mediaId);
}
