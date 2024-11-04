<?php

use App\Http\Controllers\DeliveryDriver\DeliveryDriverAuthController;
use App\Http\Controllers\DeliveryDriver\DeliveryDriverController;
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

Route::middleware(['auth:sanctum', 'auth:delivery-driver-api'])->group(function () {
    Route::get('/profile/show', [DeliveryDriverController::class, 'profileShow'])->name('delivery_drivers.profile.show');
    Route::match(['PUT', 'PATCH'], '/profile/update', [DeliveryDriverController::class, 'profileUpdate'])->name('delivery_drivers.profile.update');

    Route::get('/{id}', [DeliveryDriverController::class, 'show'])->name('delivery_drivers.show');
    Route::match(['PUT', 'PATCH'], '/{id}', [DeliveryDriverController::class, 'update'])->name('delivery_drivers.update');

    Route::post('/documents/upload', [DeliveryDriverController::class, 'uploadDocument']);
    Route::get('/documents/{fileId}/download', [DeliveryDriverController::class, 'downloadFile']);
    Route::get('/{id}/documents', [DeliveryDriverController::class, 'getDocumentsList']);

});
