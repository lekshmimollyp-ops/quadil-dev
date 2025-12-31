<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;

Route::prefix('v1')->group(function () {
    Route::get('/orders/summary', [OrderController::class, 'getSummary']);
    Route::get('/orders/recent', [OrderController::class, 'getRecent']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'createOrder']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
});
