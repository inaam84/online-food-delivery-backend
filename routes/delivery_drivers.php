<?php

use App\Http\Controllers\DeliveryDriver\DeliveryDriverAuthController;
use App\Http\Controllers\DeliveryDriver\DeliveryDriverController;
use App\Http\Controllers\DeliveryDriver\DeliveryVehicleController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [DeliveryDriverAuthController::class, 'register']);

Route::post('/email/resend', [DeliveryDriverAuthController::class, 'resendVerificationEmail'])
    ->name('driver.verification.resend');

Route::get('email/verify/{id}/{hash}', [DeliveryDriverAuthController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('driver_verification.verify');

Route::post('/login', [DeliveryDriverAuthController::class, 'login'])
    ->middleware(['throttle:6,1'])
    ->name('driver.login');

Route::middleware('auth:sanctum', 'auth:delivery-driver-api')->group(function () {
    Route::get('/profile', [DeliveryDriverController::class, 'profile']);
    Route::match(['PUT', 'PATCH'], '/profile/update', [DeliveryDriverController::class, 'updateProfile']);

    Route::get('/vehicles', [DeliveryVehicleController::class, 'index']);
    Route::post('/vehicle', [DeliveryVehicleController::class, 'store']);
});
