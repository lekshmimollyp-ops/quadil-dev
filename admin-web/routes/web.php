<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/accounts/wallets', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallets.index');
    Route::post('/accounts/wallets/topup', [\App\Http\Controllers\WalletController::class, 'topup'])->name('wallets.topup');
    Route::patch('/accounts/wallets/credit-limit', [\App\Http\Controllers\WalletController::class, 'updateCreditLimit'])->name('wallets.credit-limit');

    Route::resource('merchants', \App\Http\Controllers\MerchantController::class);
    Route::get('/merchants-hierarchy', [\App\Http\Controllers\MerchantController::class, 'hierarchy'])->name('merchants.hierarchy');
    Route::post('/merchants/associate-freelancer', [\App\Http\Controllers\MerchantController::class, 'associateFreelancer'])->name('merchants.associate-freelancer');

    Route::resource('agents', \App\Http\Controllers\AgentController::class);
    Route::patch('/agents/{id}/status', [\App\Http\Controllers\AgentController::class, 'updateStatus'])->name('agents.update-status');
    Route::patch('/agents/{id}/approve', [\App\Http\Controllers\AgentController::class, 'approve'])->name('agents.approve');
    Route::post('/agents/associate', [\App\Http\Controllers\AgentController::class, 'associateWithMerchant'])->name('agents.associate');
    Route::post('/agents/disassociate', [\App\Http\Controllers\AgentController::class, 'disassociateFromMerchant'])->name('agents.disassociate');

    Route::resource('orders', \App\Http\Controllers\OrderController::class)->only(['index', 'show']);
    Route::post('/orders/{id}/assign', [\App\Http\Controllers\OrderController::class, 'assign'])->name('orders.assign');

    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/merchant', [\App\Http\Controllers\AnalyticsController::class, 'getMerchantStats'])->name('analytics.merchant');

    // Geolocation Masters
    Route::get('/geo/masters', [\App\Http\Controllers\GeoController::class, 'indexMasters'])->name('geo.masters.index');
    Route::post('/geo/countries', [\App\Http\Controllers\GeoController::class, 'storeCountry'])->name('geo.countries.store');
    Route::put('/geo/countries/{id}', [\App\Http\Controllers\GeoController::class, 'updateCountry'])->name('geo.countries.update');
    Route::patch('/geo/countries/{id}', [\App\Http\Controllers\GeoController::class, 'destroyCountry'])->name('geo.countries.destroy');

    Route::post('/geo/states', [\App\Http\Controllers\GeoController::class, 'storeState'])->name('geo.states.store');
    Route::put('/geo/states/{id}', [\App\Http\Controllers\GeoController::class, 'updateState'])->name('geo.states.update');
    Route::patch('/geo/states/{id}', [\App\Http\Controllers\GeoController::class, 'destroyState'])->name('geo.states.destroy');

    Route::get('/cities', [\App\Http\Controllers\GeoController::class, 'indexCities'])->name('cities.index');
    Route::post('/cities', [\App\Http\Controllers\GeoController::class, 'storeCity'])->name('cities.store');
    Route::put('/cities/{id}', [\App\Http\Controllers\GeoController::class, 'updateCity'])->name('cities.update');
    Route::patch('/cities/{id}', [\App\Http\Controllers\GeoController::class, 'destroyCity'])->name('cities.destroy');

    Route::get('/areas', [\App\Http\Controllers\GeoController::class, 'indexAreas'])->name('areas.index');
    Route::post('/areas', [\App\Http\Controllers\GeoController::class, 'storeArea'])->name('areas.store');
    Route::put('/areas/{id}', [\App\Http\Controllers\GeoController::class, 'updateArea'])->name('areas.update');
    Route::patch('/areas/{id}', [\App\Http\Controllers\GeoController::class, 'destroyArea'])->name('areas.destroy');

    // Payment & Accounts
    Route::get('/accounts/ledgers', [\App\Http\Controllers\AccountsController::class, 'index'])->name('accounts.ledgers');
    Route::get('/accounts/ledgers/{tenant_id}', [\App\Http\Controllers\AccountsController::class, 'merchantLedger'])->name('accounts.merchant-ledger');
    Route::get('/accounts/reports/cod-summary', [\App\Http\Controllers\AccountsController::class, 'codSummary'])->name('accounts.cod-summary');

    Route::get('/accounts/wallets', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallets.index');
    Route::post('/accounts/wallets/topup', [\App\Http\Controllers\WalletController::class, 'topup'])->name('wallets.topup');
});

require __DIR__.'/auth.php';
