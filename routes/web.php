<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

// Authentication routes (Laravel provides these by default)
Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Shifts
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::post('shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');
    Route::get('shifts/active', [ShiftController::class, 'getActive'])->name('shifts.active');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('products/stock/add', [ProductController::class, 'addStock'])->name('stock.add');

    // Sales
    Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
    Route::get('sales/{sale}/receipt', [SalesController::class, 'receipt'])->name('sales.receipt');
    
    // POS Product Search Routes (JSON endpoints for AJAX)
    Route::get('pos/products', [SalesController::class, 'getProducts'])->name('pos.products');
    Route::get('pos/products/search', [SalesController::class, 'searchProduct'])->name('pos.search');

    // Logout
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});