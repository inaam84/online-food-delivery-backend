<?php

use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [CustomerAuthController::class, 'register']);

Route::post('/email/resend', [CustomerAuthController::class, 'resendVerificationEmail'])
    ->name('customer.verification.resend');

Route::get('email/verify/{id}/{hash}', [CustomerAuthController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('customer_verification.verify');

Route::post('/login', [CustomerAuthController::class, 'login'])
    ->middleware(['throttle:6,1'])
    ->name('customer.login');

Route::middleware('auth:sanctum', 'auth:customer-api')->group(function () {
    Route::get('/profile/show', [CustomerController::class, 'profileShow'])->name('customers.profile.show');
    Route::match(['PUT', 'PATCH'], '/profile/update', [CustomerController::class, 'profileUpdate'])->name('customers.profile.update');

    Route::get('/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::match(['PUT', 'PATCH'], '/{id}', [CustomerController::class, 'update'])->name('customers.update');
});
