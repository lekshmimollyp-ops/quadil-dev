<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AccountingController;

Route::prefix('v1')->group(function () {
    Route::post('/entries', [AccountingController::class, 'createEntry']);
    Route::get('/entries/{tenant_id}', [AccountingController::class, 'getHistory']);
    Route::get('/summary', [AccountingController::class, 'getPlatformSummary']);
});
