<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TrackingController;

Route::prefix('v1')->group(function () {
    Route::post('/ping', [TrackingController::class, 'ping']);
    Route::get('/locate/{agent_id}', [TrackingController::class, 'getLatest']);
    Route::post('/check-geofence', [TrackingController::class, 'checkGeofence']);
});
