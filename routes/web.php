<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BudgetAllocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletTransactionController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Route::inertia('/', 'Welcome', [
//     'canRegister' => Features::enabled(Features::registration()),
// ])->name('home');

Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::inertia('/', 'Dashboard', [
    //     'canRegister' => Features::enabled(Features::registration()),
    // ])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('wallets', WalletController::class);
    Route::resource('budget', BudgetAllocationController::class);
    Route::resource('loans', LoanController::class);
    Route::resource('transactions', WalletTransactionController::class)->only('index', 'create', 'edit');
});

require __DIR__.'/settings.php';
require __DIR__.'/api.php';
