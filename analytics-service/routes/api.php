<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnalyticsController;

Route::prefix('v1')->group(function () {
    Route::get('/merchant/{tenant_id}', [AnalyticsController::class, 'getMerchantStats']);
    Route::get('/platform', [AnalyticsController::class, 'getPlatformStats']);
    Route::get('/revenue/today', [AnalyticsController::class, 'getTodayRevenue']);
    Route::post('/refresh', [AnalyticsController::class, 'refresh']);
});
