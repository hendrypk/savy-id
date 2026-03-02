<?php

use App\Http\Controllers\Api\TransactionCategoryController;
use Illuminate\Support\Facades\Route;

// PAKAI 'auth' SAJA, JANGAN 'auth:sanctum'
Route::middleware('auth')
    ->prefix('api') // Tambahkan prefix manual jika perlu
    ->name('api.')
    ->group(function () {
        Route::apiResource('transaction-categories', TransactionCategoryController::class);
    });