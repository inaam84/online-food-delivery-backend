<?php

namespace App\Http\Controllers\DeliveryDriver;

use App\Enums\DeliveryDriverRegistrationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryDriver\DeliveryDriverUpdateRequest;
use App\Http\Resources\DeliveryDriver\DriverResource;
use App\Interfaces\DeliveryDriverRepositoryInterface;
use App\Interfaces\MediaRepositoryInterface;
use App\Models\DeliveryDriver;
use App\Notifications\DriverRegistrationStatusUpdated;
use App\Services\Validation\FileValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class DeliveryDriverController extends Controller
{
    private DeliveryDriverRepositoryInterface $driverRepository;

    public function __construct(DeliveryDriverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('index', DeliveryDriver::class);

        return DriverResource::collection(
            $this->driverRepository->getAllDrivers(array_filter($request->all()))
        );
    }

    public function profileShow()
    {
        Gate::authorize('profile', DeliveryDriver::class);

        return response()->json(new DriverResource(auth()->user()));
    }

    public function profileUpdate(DeliveryDriverUpdateRequest $request)
    {
        Gate::authorize('profile', DeliveryDriver::class);

        $this->driverRepository->updateDriver(auth()->user()->id, $request->validated());

        return jsonResponse(['message' => __('Profile has been updated successfully.')]);
    }

    public function show($id)
    {
        $driver = $this->driverRepository->getDriverById($id);
        if (! $driver) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('show', $driver);

        return response()->json(new DriverResource($driver));
    }

    public function update($id, DeliveryDriverUpdateRequest $request)
    {
        $driver = $this->driverRepository->getDriverById($id);
        if (! $driver) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('update', $driver);

        $this->driverRepository->updateDriver($driver->id, $request->validated());

        return jsonResponse(['message' => __('Profile has been updated successfully.')]);
    }

    public function updateRegistrationStatus($id, Request $request)
    {
        $driver = $this->driverRepository->getDriverById($id);
        if (! $driver) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'registration_status' => ['required', new Enum(DeliveryDriverRegistrationStatus::class)],
        ]);

        Gate::authorize('update', $driver);

        $this->driverRepository->updateDriver($driver->id, [
            'registration_status' => $request->registration_status,
        ]);

        $driver->notify(new DriverRegistrationStatusUpdated($request->registration_status));

        return jsonResponse(['message' => __('Driver status has been updated successfully.')]);
    }

    public function uploadDocument(Request $request, MediaRepositoryInterface $mediaRepository)
    {
        Gate::authorize('uploadDocument', DeliveryDriver::class);

        FileValidationService::validateFile($request->file('file'));

        $driver = auth()->user();
        if (isUser(auth()->user())) {
            $request->validate(['delivery_driver_id' => 'required|uuid']);
            $driver = $this->driverRepository->getDriverById($request->delivery_driver_id);
        }

        $mediaRepository->uploadMediaFromRequest($driver, 'file', 'driver_documents');

        return jsonResponse(['message' => __('Document uploaded successfully.')]);
    }

    public function getDocumentsList($id)
    {
        $driver = $this->driverRepository->getDriverById($id);
        if (! $driver) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('getDocumentsList', $driver);

        $files = [];
        foreach ($driver->media as $media) {
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
        Gate::authorize('downloadFile', DeliveryDriver::class);

        $media = null;
        if (isDeliveryDriver(auth()->user())) {
            $media = $this->driverRepository->getDriverFile(auth()->user()->id, $fileId);
        }
        if (isUser(auth()->user())) {
            $media = $mediaRepository->getMediaByUuid($fileId);
        }

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
