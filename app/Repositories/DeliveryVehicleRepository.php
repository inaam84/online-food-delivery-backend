<?php

namespace App\Repositories;

use App\Interfaces\DeliveryVehicleRepositoryInterface;
use App\Models\DeliveryVehicle;

class DeliveryVehicleRepository implements DeliveryVehicleRepositoryInterface
{
    public function getAllVehicles(array $filters = [])
    {
        $page = 50;

        $query = DeliveryVehicle::query();
        if (array_key_exists('registration_number', $filters)) {
            $query->where('registration_number', 'LIKE', '%'.$filters['first_name'].'%');
        }
        if (array_key_exists('year', $filters)) {
            $query->where('year', $filters['year']);
        }
        if (array_key_exists('status', $filters)) {
            $query->where('status', $filters['status']);
        }
        if (array_key_exists('type', $filters)) {
            $query->where('type', $filters['type']);
        }
        if (array_key_exists('page', $filters)) {
            $page = $filters['page'];
        }
        $page = $page > 50 ? 50 : $page;

        return $query->paginate($page);
    }

    public function getVehicleById($vehicleId)
    {
        return DeliveryVehicle::find($vehicleId);
    }

    public function deleteVehicle($vehicleId) {}

    public function createVehicle(array $vehicleDetails)
    {
        return DeliveryVehicle::create($vehicleDetails);
    }

    public function updateVehicle($vehicleId, array $newDetails)
    {
        $vehicle = $this->getVehicleById($vehicleId);
        if ($vehicle) {
            $vehicle->update($newDetails);
        }

        return $vehicle;
    }

    public function attachDocument($vehicleId, $documentId) {}

    public function removeDocument($vehicleId, $documentId) {}
}
