<?php

namespace App\Http\Controllers\DeliveryDriver;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryDriver\DeliveryVehicleStoreUpdateRequest;
use App\Http\Resources\DeliveryDriver\DeliveryVehicleResource;
use App\Interfaces\DeliveryVehicleRepositoryInterface;
use App\Models\DeliveryDriver;
use App\Models\DeliveryVehicle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class DeliveryVehicleController extends Controller
{
    private DeliveryVehicleRepositoryInterface $vehicleRepository;

    public function __construct(DeliveryVehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function index(Request $request)
    {
        if (auth()->user() instanceof DeliveryDriver) {
            return DeliveryVehicleResource::collection(
                auth()->user()->vehicles
            );
        }

        return DeliveryVehicleResource::collection(
            $this->vehicleRepository->getAllVehicles(array_filter($request->all()))
        );
    }

    public function store(DeliveryVehicleStoreUpdateRequest $request)
    {
        Gate::authorize('create', DeliveryVehicle::class);

        // sanity check driver can only provide his/her own id
        abort_if(
            isDeliveryDriver(auth()->user()) && auth()->user()->id != $request->delivery_driver_id,
            Response::HTTP_BAD_REQUEST,
            'Bad request'
        );

        $vehicle = $this->vehicleRepository->createVehicle($request->validated());

        return response()->json(new DeliveryVehicleResource($vehicle));
    }
}
