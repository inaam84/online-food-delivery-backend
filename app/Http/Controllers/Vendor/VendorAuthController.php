<?php

namespace App\Http\Controllers\Vendor;

use App\Events\RegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\VendorRegistrationRequest;
use App\Interfaces\VendorRepositoryInterface;
use App\Models\Vendor;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class VendorAuthController extends Controller
{
    private VendorRepositoryInterface $vendorRepository;

    private AuthService $authService;

    public function __construct(VendorRepositoryInterface $vendorRepository, AuthService $authService)
    {
        $this->vendorRepository = $vendorRepository;
        $this->authService = $authService;
    }

    public function register(VendorRegistrationRequest $request)
    {
        $Vendor = $this->vendorRepository->createVendor($request->validated());

        event(new RegisteredEvent($Vendor));

        return response()->json(['message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.']);
    }

    public function resendVerificationEmail(Request $request)
    {
        return $this->authService->resendVerificationEmail($request, Vendor::class);
    }

    public function verify(Request $request, $id, $hash)
    {
        return $this->authService->verify($request, Vendor::class, $id, $hash);
    }

    public function login(Request $request)
    {
        return $this->authService->login($request, Vendor::class);
    }
}
