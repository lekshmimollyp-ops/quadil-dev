<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgentController;

Route::prefix('v1')->group(function () {
    Route::get('/agents', [AgentController::class, 'index']);
    Route::post('/agents', [AgentController::class, 'registerAgent']);
    Route::get('/agents/{id}', [AgentController::class, 'show']);
    Route::patch('/agents/{id}/approve', [AgentController::class, 'approve']);
    Route::delete('/agents/{id}', [AgentController::class, 'destroy']);
    Route::patch('/agents/{id}/status', [AgentController::class, 'updateStatus']);
    
    Route::post('/vehicles', [AgentController::class, 'addVehicle']);
});
