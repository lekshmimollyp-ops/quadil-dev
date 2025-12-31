<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WalletController;

Route::prefix('v1')->group(function () {
    Route::get('/balance/{tenant_id}', [WalletController::class, 'getBalance']);
    Route::get('/entries/{tenant_id}', [WalletController::class, 'getLedger']);
    Route::get('/summary', [WalletController::class, 'getSummary']);
    Route::post('/topup', [WalletController::class, 'topup']);
    Route::post('/deduct', [WalletController::class, 'deduct']);
    Route::patch('/credit-limit', [WalletController::class, 'updateCreditLimit']);
});
