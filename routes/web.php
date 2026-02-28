<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('budget', BudgetController::class)->names('budgets');
    Route::resource('loans', LoanController::class);
});

require __DIR__.'/settings.php';
