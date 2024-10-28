<?php

use App\Http\Controllers\Vendor\VendorAuthController;
use App\Http\Controllers\Vendor\VendorController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [VendorAuthController::class, 'register']);

Route::post('/email/resend', [VendorAuthController::class, 'resendVerificationEmail'])
    ->name('vendor.verification.resend');

Route::get('email/verify/{id}/{hash}', [VendorAuthController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('vendor_verification.verify');

Route::post('/login', [VendorAuthController::class, 'login'])
    ->middleware(['throttle:6,1'])
    ->name('vendor.login');

Route::middleware('auth:sanctum', 'auth:vendor-api')->group(function () {
    Route::get('/profile/show', [VendorController::class, 'profileShow'])->name('vendors.profile.show');
    Route::match(['PUT', 'PATCH'], '/profile/update', [VendorController::class, 'profileUpdate'])->name('vendors.profile.update');

    Route::get('/{id}', [VendorController::class, 'show'])->name('vendors.show');
    Route::match(['PUT', 'PATCH'], '/{id}', [VendorController::class, 'update'])->name('vendors.update');
});
