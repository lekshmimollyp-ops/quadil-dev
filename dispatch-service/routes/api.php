<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DispatchController;

Route::prefix('v1')->group(function () {
    Route::post('/drivers', [DispatchController::class, 'registerDriver']);
    Route::patch('/drivers/{id}/status', [DispatchController::class, 'updateStatus']);
    Route::post('/assign', [DispatchController::class, 'assignOrder']);
    Route::get('/drivers/online', [DispatchController::class, 'getOnlineDrivers']);

    // Driver App Routes
    Route::get('/jobs', [DispatchController::class, 'getAvailableJobs']);
    Route::post('/jobs/{id}/accept', [DispatchController::class, 'acceptJob']);
    Route::post('/jobs/{id}/pickup', [DispatchController::class, 'pickupJob']);
    Route::post('/jobs/{id}/deliver', [DispatchController::class, 'completeJob']);
});
