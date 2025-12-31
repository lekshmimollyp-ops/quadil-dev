<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NotificationController;

Route::prefix('v1')->group(function () {
    Route::post('/send', [NotificationController::class, 'send']);
    Route::get('/history/{user_id}', [NotificationController::class, 'history']);
});
