<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TenantController;

Route::prefix('v1')->group(function () {
    Route::get('/tenants', [TenantController::class, 'index']);
    Route::get('/tenants/hierarchy', [TenantController::class, 'indexHierarchy']);
    Route::post('/tenants', [TenantController::class, 'onboard']);
    Route::get('/tenants/{id}', [TenantController::class, 'show']);
    Route::put('/tenants/{id}', [TenantController::class, 'update']);
    Route::delete('/tenants/{id}', [TenantController::class, 'destroy']);
    
    Route::post('/tenants/associate-freelancer', [TenantController::class, 'associateFreelancer']);
    Route::post('/tenants/disassociate-freelancer', [TenantController::class, 'removeFreelancerAssociation']);
    Route::get('/tenants/{id}/associations', [TenantController::class, 'getAssociations']);
    Route::get('/freelancers/{userId}/associations', [TenantController::class, 'getFreelancerAssociations']);
    Route::get('/config/{slug}', [TenantController::class, 'getConfig']);
});
