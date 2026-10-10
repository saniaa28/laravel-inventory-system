<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk menampilkan halaman daftar barang
Route::get('/products', [ProductController::class, 'index']);

// Rute untuk membuka halaman form tambah barang
Route::get('/products/create', [ProductController::class, 'create']);

// Rute untuk menyimpan data dari form ke database
Route::post('/products', [ProductController::class, 'store']);

// Rute untuk membuka halaman form edit barang
Route::get('/products/{id}/edit', [ProductController::class, 'edit']);

// Rute untuk memproses update (simpan perubahan) data ke database
Route::put('/products/{id}', [ProductController::class, 'update']);

// Rute untuk menghapus data dari database
Route::delete('/products/{id}', [ProductController::class, 'destroy']);