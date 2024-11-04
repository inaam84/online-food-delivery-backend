<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorUpdateRequest;
use App\Http\Resources\Vendor\VendorResource;
use App\Interfaces\MediaRepositoryInterface;
use App\Interfaces\VendorRepositoryInterface;
use App\Models\Vendor;
use App\Services\Validation\FileValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class VendorController extends Controller
{
    private VendorRepositoryInterface $vendorRepository;

    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('index', Vendor::class);

        return VendorResource::collection(
            $this->vendorRepository->getAllVendors(array_filter($request->all()))
        );
    }

    public function profileShow()
    {
        Gate::authorize('profile', Vendor::class);

        return response()->json(new VendorResource(auth()->user()));
    }

    public function profileUpdate(VendorUpdateRequest $request, MediaRepositoryInterface $mediaRepository)
    {
        Gate::authorize('profile', Vendor::class);

        $this->vendorRepository->updateVendor(auth()->user()->id, $request->validated());

        // if($request->hasFile('logo'))

        FileValidationService::validateVendorLogo($request->file('logo'));

        $mediaRepository->uploadMediaFromRequest(auth()->user(), 'logo', 'vendor_logos');

        return jsonResponse(['message' => __('Profile has been updated successfully.')]);
    }

    public function show($id)
    {
        $Vendor = $this->vendorRepository->getVendorById($id);
        if (! $Vendor) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('show', $Vendor);

        return response()->json(new VendorResource($Vendor));
    }

    public function update($id, VendorUpdateRequest $request)
    {
        $Vendor = $this->vendorRepository->getVendorById($id);
        if (! $Vendor) {
            return jsonResponse(['message' => __('Record not found.')], Response::HTTP_NOT_FOUND);
        }

        Gate::authorize('update', $Vendor);

        $this->vendorRepository->updateVendor($Vendor->id, $request->validated());

        return jsonResponse(['message' => __('Profile has been updated successfully.')]);
    }
}
