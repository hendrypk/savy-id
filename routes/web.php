<?php

use App\Http\Controllers\BudgetAllocationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('wallets', WalletController::class);
    Route::resource('budget', BudgetAllocationController::class);
    Route::resource('loans', LoanController::class);
});

require __DIR__.'/settings.php';
