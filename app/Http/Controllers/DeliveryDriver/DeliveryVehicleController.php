<?php

namespace App\Http\Controllers\DeliveryDriver;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryDriver\DeliveryVehicleStoreUpdateRequest;
use App\Http\Resources\DeliveryDriver\DeliveryVehicleResource;
use App\Interfaces\DeliveryVehicleRepositoryInterface;
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
        Gate::authorize('index', DeliveryVehicle::class);

        if (isDeliveryDriver(auth()->user())) {
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
        if (isDeliveryDriver(auth()->user()) && auth()->user()->id != $request->delivery_driver_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $vehicle = $this->vehicleRepository->createVehicle($request->validated());

        return response()->json(new DeliveryVehicleResource($vehicle));
    }

    public function update($id, DeliveryVehicleStoreUpdateRequest $request)
    {
        Gate::authorize('create', DeliveryVehicle::class);

        $vehicle = $this->vehicleRepository->getVehicleById($id);
        if (is_null($vehicle)) {
            return jsonResponse(['message' => __('Not Found')], Response::HTTP_NOT_FOUND);
        }

        if ($request->delivery_driver_id != $vehicle->delivery_driver_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $vehicle = $this->vehicleRepository->updateVehicle($id, $request->validated());

        return response()->json(new DeliveryVehicleResource($vehicle));
    }
}
