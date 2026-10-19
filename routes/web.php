<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TransactionHistoryController;
use App\Http\Controllers\Admin\CashController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Cashier\TransactionController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role->name === \App\Enums\RoleEnum::OWNER->value) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('pos.index');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'active_user'])->group(function () {
    // Shared route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin & Owner Only
    Route::middleware(['role:admin,owner'])->group(function () {
        Route::resource('/admin/products', ProductController::class)->names('admin.products');
        Route::resource('/admin/categories', CategoryController::class)->except(['create','edit','show'])->names('admin.categories');
        Route::resource('/admin/suppliers', SupplierController::class)->names('admin.suppliers');
        
        Route::get('/admin/transactions', [TransactionHistoryController::class, 'index'])->name('admin.transactions.index');
        Route::get('/admin/transactions/{transaction}', [TransactionHistoryController::class, 'show'])->name('admin.transactions.show');
        Route::post('/admin/transactions/{transaction}/void', [TransactionHistoryController::class, 'void'])->name('admin.transactions.void');
        
        Route::get('/admin/cash', [CashController::class, 'index'])->name('admin.cash.index');
        Route::post('/admin/cash', [CashController::class, 'store'])->name('admin.cash.store');
        
        Route::get('/admin/reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
        Route::get('/admin/reports/profit', [ReportController::class, 'profit'])->name('admin.reports.profit');
        
        Route::resource('/admin/users', UserController::class)->except(['show'])->names('admin.users');
        Route::post('/admin/users/{user}/toggle', [UserController::class, 'toggleActive'])->name('admin.users.toggle');
    });

    // Cashier Only (and admin for testing)
    Route::middleware(['role:cashier,admin'])->group(function () {
        Route::get('/pos', [TransactionController::class, 'index'])->name('pos.index');
        Route::post('/pos', [TransactionController::class, 'store'])->name('pos.store');
    });
});

require __DIR__.'/auth.php';
