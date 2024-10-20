<?php

use App\Http\Controllers\Customer\CustomerAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/customer/register', [CustomerAuthController::class, 'register']);

Route::get('customer/email/verify/{id}/{hash}', [CustomerAuthController::class, 'verify'])
    ->name('customer_verification.verify');
