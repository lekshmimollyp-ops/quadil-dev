<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebhookController;

Route::prefix('v1')->group(function () {
    Route::post('/webhooks', [WebhookController::class, 'register']);
    Route::get('/webhooks', [WebhookController::class, 'index']);
    Route::post('/dispatch', [WebhookController::class, 'dispatchEvent']);
});
