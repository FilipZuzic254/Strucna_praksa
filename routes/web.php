<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorExportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inventory-items/{inventoryItem}/export-sensors', [SensorExportController::class, 'export'])
    ->name('item.sensor.export')
    ->middleware(['auth']);
