<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorExportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inventory-items/{inventoryItem}/export-sensors', [SensorExportController::class, 'SingleSensorExport'])
    ->name('item.sensors.export')
    ->middleware(['auth']);

Route::get('/products/export-sensors', [SensorExportController::class, 'exportBulkProducts'])
    ->name('products.sensors.export')
    ->middleware(['auth']);

Route::get('/inventory-items/export-sensors', [SensorExportController::class, 'exportBulkItems'])
    ->name('items.sensors.export')
    ->middleware(['auth']);