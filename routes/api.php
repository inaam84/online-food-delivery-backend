<?php

use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/customer/register', [CustomerAuthController::class, 'register']);

Route::post('/customer/email/resend', [CustomerAuthController::class, 'resendVerificationEmail'])
    ->name('customer.verification.resend');

Route::get('customer/email/verify/{id}/{hash}', [CustomerAuthController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('customer_verification.verify');

Route::post('/customer/login', [CustomerAuthController::class, 'login'])
    ->middleware(['throttle:6,1'])
    ->name('customer.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/customer/profile', [CustomerController::class, 'profile']);
    Route::post('/customer/logout', [CustomerAuthController::class, 'logout']);
    Route::match(['PUT', 'PATCH'], '/customer/profile/update', [CustomerController::class, 'updateProfile']);
});

/*
Route::middleware('auth:sanctum')->get('/customer/profile', function (Request $request) {
    \Log::info($request->bearerToken());  // Log the token for debugging
    return $request->user();  // Should return the authenticated user
});
*/
