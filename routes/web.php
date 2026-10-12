<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Halaman untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Halaman yang wajib login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Hanya admin: kelola produk, kategori, supplier, dan user.
    // Group ini ditaruh lebih dulu supaya /products/create tidak dianggap /products/{product}.
    Route::middleware('role:admin')->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::post('products/{product}/suppliers', [ProductController::class, 'attachSupplier'])->name('products.suppliers.store');
        Route::delete('products/{product}/suppliers/{supplier}', [ProductController::class, 'detachSupplier'])->name('products.suppliers.destroy');

        Route::resource('categories', CategoryController::class)->except(['create', 'show']);

        Route::resource('suppliers', SupplierController::class)->except(['create', 'show']);
        Route::patch('suppliers/{supplier}/archive', [SupplierController::class, 'archive'])->name('suppliers.archive');
        Route::patch('suppliers/{supplier}/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');

        Route::resource('users', UserController::class)->except(['create', 'show']);
    });

    // Admin dan staff: lihat produk, catat transaksi stok, lihat laporan
    Route::resource('products', ProductController::class)->only(['index', 'show']);

    Route::get('stock/in', [StockController::class, 'createIn'])->name('stock.in');
    Route::post('stock/in', [StockController::class, 'storeIn'])->name('stock.in.store');
    Route::get('stock/out', [StockController::class, 'createOut'])->name('stock.out');
    Route::post('stock/out', [StockController::class, 'storeOut'])->name('stock.out.store');
    Route::get('stock/history', [StockController::class, 'history'])->name('stock.history');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});
