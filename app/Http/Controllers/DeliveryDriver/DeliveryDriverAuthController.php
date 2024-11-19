<?php

namespace App\Http\Controllers\DeliveryDriver;

use App\Enums\DeliveryDriverRegistrationStatus;
use App\Events\RegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryDriver\DeliveryDriverRegistrationRequest;
use App\Interfaces\DeliveryDriverRepositoryInterface;
use App\Models\DeliveryDriver;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class DeliveryDriverAuthController extends Controller
{
    private DeliveryDriverRepositoryInterface $driverRepository;

    private AuthService $authService;

    public function __construct(DeliveryDriverRepositoryInterface $driverRepository, AuthService $authService)
    {
        $this->driverRepository = $driverRepository;
        $this->authService = $authService;
    }

    public function register(DeliveryDriverRegistrationRequest $request)
    {
        $driverData = $request->validated();
        $driverData['registration_status'] = DeliveryDriverRegistrationStatus::PENDING_APPROVAL;
        $driver = $this->driverRepository->createDriver($driverData);

        event(new RegisteredEvent($driver));

        return response()->json(['message' => 'Registration successful. Please check your inbox and verify your email. It might take few minutes so please be patient.']);
    }

    public function resendVerificationEmail(Request $request)
    {
        return $this->authService->resendVerificationEmail($request, DeliveryDriver::class);
    }

    public function verify(Request $request, $id, $hash)
    {
        return $this->authService->verify($request, DeliveryDriver::class, $id, $hash);
    }

    public function login(Request $request)
    {
        return $this->authService->login($request, DeliveryDriver::class);
    }
}
