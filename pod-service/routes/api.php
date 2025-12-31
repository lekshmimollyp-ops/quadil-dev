<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PodController;

Route::prefix('v1')->group(function () {
    Route::post('/generate-otp', [PodController::class, 'generateOtp']);
    Route::post('/verify-otp', [PodController::class, 'verifyOtp']);
    Route::post('/capture', [PodController::class, 'capturePod']);
    Route::get('/pods/{order_id}', [PodController::class, 'show']);
});
