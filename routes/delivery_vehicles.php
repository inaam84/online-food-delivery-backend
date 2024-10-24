<?php

use App\Http\Controllers\DeliveryDriver\DeliveryVehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum', 'auth:delivery-driver-api')->group(function () {
    Route::get('/', [DeliveryVehicleController::class, 'index'])->name('delivery_vehicles.index');
    Route::post('/', [DeliveryVehicleController::class, 'store'])->name('delivery_vehicles.store');
    Route::get('/{id}', [DeliveryVehicleController::class, 'show'])->name('delivery_vehicles.show');
    Route::match(['PUT', 'PATCH'], '/{id}', [DeliveryVehicleController::class, 'update'])->name('delivery_vehicles.update');
    Route::delete('/{id}', [DeliveryVehicleController::class, 'destroy'])->name('delivery_vehicles.destroy');

    Route::post('/documents/upload', [DeliveryVehicleController::class, 'uploadDocument']);
    Route::get('/documents/{fileId}/download', [DeliveryVehicleController::class, 'downloadFile']);
    Route::get('/{id}/documents', [DeliveryVehicleController::class, 'getDocumentsList']);
});
