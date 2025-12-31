<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GeoController;

Route::prefix('v1')->group(function () {
    Route::get('/cities', [GeoController::class, 'indexCities']);
    Route::post('/cities', [GeoController::class, 'createCity']);
    Route::put('/cities/{id}', [GeoController::class, 'updateCity']);
    Route::patch('/cities/{id}', [GeoController::class, 'deleteCity']);

    Route::get('/countries', [GeoController::class, 'indexCountries']);
    Route::post('/countries', [GeoController::class, 'createCountry']);
    Route::put('/countries/{id}', [GeoController::class, 'updateCountry']);
    Route::patch('/countries/{id}', [GeoController::class, 'toggleCountry']);

    Route::get('/states', [GeoController::class, 'indexStates']);
    Route::post('/states', [GeoController::class, 'createState']);
    Route::put('/states/{id}', [GeoController::class, 'updateState']);
    Route::patch('/states/{id}', [GeoController::class, 'toggleState']);

    Route::get('/areas', [GeoController::class, 'indexAreas']);
    Route::post('/areas', [GeoController::class, 'createArea']);
    Route::put('/areas/{id}', [GeoController::class, 'updateArea']);
    Route::patch('/areas/{id}', [GeoController::class, 'deleteArea']);

    Route::get('/check-coverage', [GeoController::class, 'checkCoverage']);
});
