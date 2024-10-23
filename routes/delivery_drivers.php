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
    Route::get('/profile/show', [DeliveryDriverController::class, 'profileShow'])->name('delivery_drivers.profile.show');
    Route::match(['PUT', 'PATCH'], '/profile/update', [DeliveryDriverController::class, 'profileUpdate'])->name('delivery_drivers.profile.update');

    Route::get('/{id}', [DeliveryDriverController::class, 'show'])->name('delivery_drivers.show');
    Route::match(['PUT', 'PATCH'], '/{id}', [DeliveryDriverController::class, 'update'])->name('delivery_drivers.update');
    
    Route::post('/documents/upload', [DeliveryDriverController::class, 'uploadDocument']);
    Route::get('/documents/{fileId}/download', [DeliveryDriverController::class, 'downloadFile']);
    Route::get('/{id}/documents', [DeliveryDriverController::class, 'getDocumentsList']);

    Route::get('/vehicles', [DeliveryVehicleController::class, 'index'])->name('delivery_vehicles.index');
    Route::post('/vehicle', [DeliveryVehicleController::class, 'store'])->name('delivery_vehicles.store');
    Route::get('/vehicles/{id}', [DeliveryVehicleController::class, 'show'])->name('delivery_vehicles.show');
    Route::match(['PUT', 'PATCH'], '/vehicles/{id}', [DeliveryVehicleController::class, 'update'])->name('delivery_vehicles.update');
    Route::delete('/vehicles/{id}', [DeliveryVehicleController::class, 'destroy'])->name('delivery_vehicles.destroy');
});
