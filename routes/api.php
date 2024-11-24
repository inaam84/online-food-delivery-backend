<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\DeliveryDriver\DeliveryDriverController;
use App\Http\Controllers\DeliveryDriver\DeliveryVehicleController;
use App\Http\Controllers\Food\FoodController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Vendor\VendorController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])
    ->middleware(['throttle:6,1'])
    ->name('user.login');

Route::middleware('auth:sanctum', 'auth:api')->group(function () {
    Route::post('/user/register', [UserController::class, 'register']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/delivery_drivers', [DeliveryDriverController::class, 'index']);
    Route::match(['PUT', 'PATCH'], '/delivery_drivers/{id}/updateRegistrationStatus', [DeliveryDriverController::class, 'updateRegistrationStatus'])
        ->name('delivery_drivers.updateRegistrationStatus');
    Route::get('/delivery_vehicles', [DeliveryVehicleController::class, 'index']);
    Route::get('/vendors', [VendorController::class, 'index']);
});

Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])
    ->name('customer.verification.resend');

Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('user_verification.verify');

Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

Route::group(['prefix' => 'customers'], function () {
    require __DIR__.'/customers.php';
});

Route::group(['prefix' => 'delivery_drivers'], function () {
    require __DIR__.'/delivery_drivers.php';
});

Route::group(['prefix' => 'delivery_vehicles'], function () {
    require __DIR__.'/delivery_vehicles.php';
});

Route::group(['prefix' => 'vendors'], function () {
    require __DIR__.'/vendors.php';
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'auth:vendor-api'])->group(function () {
    Route::apiResource('foods', FoodController::class);
});
