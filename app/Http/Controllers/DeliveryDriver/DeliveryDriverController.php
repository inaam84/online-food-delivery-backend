<?php

namespace App\Http\Controllers\DeliveryDriver;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryDriver\DeliveryDriverUpdateRequest;
use App\Http\Resources\DeliveryDriver\DriverResource;
use App\Interfaces\DeliveryDriverRepositoryInterface;
use Illuminate\Http\Request;

class DeliveryDriverController extends Controller
{
    private DeliveryDriverRepositoryInterface $driverRepository;

    public function __construct(DeliveryDriverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function index(Request $request)
    {
        return DriverResource::collection(
            $this->driverRepository->getAllDrivers(array_filter($request->all()))
        );
    }

    public function profile(Request $request)
    {
        // Retrieve the authenticated driver
        $driver = $request->user();

        if (! $driver) {
            return response()->json(['message' => 'Unauthenticated Driver.'], 401);
        }

        // Return driver profile data
        return response()->json(new DriverResource($driver));
    }

    public function updateProfile(DeliveryDriverUpdateRequest $request)
    {
        // Retrieve the authenticated driver
        $driver = $request->user();

        if (! $driver) {
            return response()->json(['message' => 'Unauthenticated Driver.'], 401);
        }

        $this->driverRepository->updateDriver($driver->id, $request->validated());

        return response()->json(['message' => 'Profile has been updated successfully.']);
    }
}
