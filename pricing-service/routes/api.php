<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PricingController;

Route::prefix('v1')->group(function () {
    Route::post('/rules', [PricingController::class, 'setRules']);
    Route::post('/calculate', [PricingController::class, 'calculate']);
});
