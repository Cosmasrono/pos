<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SystemControlController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::get('purchase-orders/{purchase_order}/receive', [PurchaseOrderController::class, 'receiveForm'])->name('purchase-orders.receive-form');
    Route::post('purchase-orders/{purchase_order}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

    Route::get('pos', [SalesController::class, 'create'])->name('sales.pos');
    Route::get('pos/products', [SalesController::class, 'getProducts'])->name('pos.products');
    Route::get('pos/search', [SalesController::class, 'searchProduct'])->name('pos.search');
    Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
    Route::get('sales/{sale}/receipt', [SalesController::class, 'receipt'])->name('sales.receipt');

    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::post('shifts/close', [ShiftController::class, 'close'])->name('shifts.close');
    Route::get('shifts/active', [ShiftController::class, 'getActive'])->name('shifts.active');

    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('expenses', ExpenseController::class);

    // Promotions
    Route::resource('promotions', PromotionController::class);

    Route::get('reports/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/pnl', [\App\Http\Controllers\ReportController::class, 'profitLoss'])->name('reports.pnl');

    // Audit Logs
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');

    // System Control (Owner Only)
    Route::get('system/control', [SystemControlController::class, 'index'])->name('system.control');
    Route::post('system/toggle', [SystemControlController::class, 'toggle'])->name('system.toggle');
    Route::post('system/subscription', [SystemControlController::class, 'updateSubscription'])->name('system.subscription.update');

    // Users
    Route::resource('users', UserController::class);

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// System Unavailable Page (Public if deactivated)
Route::get('system/unavailable', function () {
    return view('errors.system_unavailable');
})->name('system.unavailable');

// Subscription Expired Page
Route::get('system/subscription-expired', function () {
    return view('errors.subscription_expired');
})->name('subscription.expired');