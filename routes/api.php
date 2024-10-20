<?php

use App\Http\Controllers\Customer\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/customer/register', [CustomerAuthController::class, 'register']);

Route::post('/customer/email/resend', [CustomerAuthController::class, 'resendVerificationEmail'])
    ->name('customer.verification.resend');

Route::get('customer/email/verify/{id}/{hash}', [CustomerAuthController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('customer_verification.verify');
