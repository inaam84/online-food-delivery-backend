<?php

namespace App\Interfaces;

interface DeliveryVehicleRepositoryInterface
{
    public function getAllVehicles();

    public function getVehicleById($vehicleId);

    public function deleteVehicle($vehicleId);

    public function createVehicle(array $vehicleDetails);

    public function updateVehicle($vehicleId, array $newDetails);

    public function attachDocument($vehicleId, $documentId);

    public function removeDocument($vehicleId, $documentId);
}
