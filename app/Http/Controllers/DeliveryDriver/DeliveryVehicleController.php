<?php

namespace App\Http\Controllers\DeliveryDriver;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryDriver\DeliveryVehicleStoreUpdateRequest;
use App\Http\Resources\DeliveryDriver\DeliveryVehicleResource;
use App\Interfaces\DeliveryVehicleRepositoryInterface;
use App\Interfaces\MediaRepositoryInterface;
use App\Models\DeliveryVehicle;
use App\Services\Validation\FileValidationService;
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

    public function show($id)
    {
        $vehicle = $this->vehicleRepository->getVehicleById($id);

        // sanity check driver can only provide his/her own id
        if (isDeliveryDriver(auth()->user()) && auth()->user()->id != $vehicle->delivery_driver_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(new DeliveryVehicleResource($vehicle));
    }

    public function destroy($id)
    {
        $vehicle = $this->vehicleRepository->getVehicleById($id);
        if (is_null($vehicle)) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        // sanity check driver can only provide his/her own id
        if (isDeliveryDriver(auth()->user()) && auth()->user()->id != $vehicle->delivery_driver_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $vehicle->delete();

        return jsonResponse(['message' => 'Vehicle information deleted.']);
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
        $vehicle = $this->vehicleRepository->getVehicleById($id);
        if (is_null($vehicle)) {
            return jsonResponse(['message' => __('Not Found')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('update', $vehicle);

        if ($request->delivery_driver_id != $vehicle->delivery_driver_id) {
            return jsonResponse(['message' => __('Bad Request')], Response::HTTP_BAD_REQUEST);
        }

        $vehicle = $this->vehicleRepository->updateVehicle($id, $request->validated());

        return response()->json(new DeliveryVehicleResource($vehicle));
    }

    public function uploadDocument(Request $request, MediaRepositoryInterface $mediaRepository)
    {
        FileValidationService::validateFile($request->file('file'));

        $request->validate([
            'delivery_driver_id' => 'required|uuid',
            'delivery_vehicle_id' => 'required|uuid',
        ]);

        $vehicle = $this->vehicleRepository->getVehicleById($request->delivery_vehicle_id);

        Gate::authorize('uploadDocument', $vehicle);

        $mediaRepository->uploadMediaFromRequest($vehicle, 'file', 'vehicle_documents');

        return jsonResponse(['message' => __('Document uploaded successfully.')]);
    }

    public function getDocumentsList($id)
    {
        $vehicle = $this->vehicleRepository->getVehicleById($id);
        if (! $vehicle) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('getDocumentsList', $vehicle);

        $files = [];
        foreach ($vehicle->media as $media) {
            $files[] = [
                'id' => $media->uuid,
                'name' => $media->file_name,
                'collection' => $media->collection_name,
                'size' => $media->human_readable_size,
                'mime_type' => $media->mime_type,
                'uploaded_at' => $media->created_at,
            ];
        }

        return response()->json($files);
    }

    public function downloadFile($fileId, MediaRepositoryInterface $mediaRepository)
    {
        Gate::authorize('downloadFile', DeliveryVehicle::class);

        $media = $mediaRepository->getMediaByUuid($fileId);

        if (! $media) {
            return jsonResponse(['message' => __('Document with given UUID is not found.')], Response::HTTP_NOT_FOUND);
        }

        return response()->streamDownload(function () use ($media) {
            $stream = fopen($media->getPath(), 'r');
            while (! feof($stream)) {
                echo fread($stream, 1024);
            }
            fclose($stream);
        }, $media->file_name, ['Content-Type' => $media->mime_type]);
    }
}
