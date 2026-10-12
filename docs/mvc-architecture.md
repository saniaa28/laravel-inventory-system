# Arsitektur MVC INVORA Laravel

## Alur Umum

```
Request → Route → Controller → Model → Database → Response
```

| Bagian | Tugas | Contoh di project ini |
| --- | --- | --- |
| **Route** | Menerima alamat URL dan meneruskannya ke controller | `routes/web.php` |
| **Controller** | Mengatur alur proses dan memilih response | `ProductController` |
| **Model** | Mewakili tabel dan mengakses database lewat Eloquent | `Product`, `Category` |
| **View** | Menampilkan hasil ke pengguna | `resources/views/products/` |
| **Middleware** | Penjaga di depan route (login dan role) | `auth`, `EnsureRole` |
| **Form Request** | Memvalidasi input sebelum masuk controller | `StoreProductRequest` |
| **Service** | Menyimpan aturan bisnis yang dipakai berulang | `StockService` |

Controller mengatur alur, model berurusan dengan data, dan view hanya menyajikan hasil.

## Contoh Alur Lengkap: Menambah Produk

Pengguna membuka form, mengisinya, lalu menyimpan.

**1. Membuka form** `GET /products/create`

- Route: `Route::resource('products', ...)` menghubungkan URL ini ke `ProductController@create`.
- Middleware `auth` dan `role:admin` dicek lebih dulu. Staff berhenti di sini dengan 403.
- Controller mengambil daftar kategori dari model `Category`, lalu mengembalikan `view('products.create')`.
- View memakai layout `layouts/app.blade.php` dan form `products/_form.blade.php`.

**2. Mengirim form** `POST /products`

- Browser mengirim data beserta token `@csrf`. Laravel menolak form tanpa token yang valid.
- Route meneruskan ke `ProductController@store`.
- Sebelum isi method berjalan, `StoreProductRequest` memeriksa aturan: SKU unik, harga tidak negatif, stok awal lebih dari nol, dan kategori harus ada.

**3. Jika validasi gagal**

- Laravel otomatis mengembalikan pengguna ke form.
- Pesan error tampil di bawah kolom yang salah lewat `@error`, dan isian lama tetap terisi lewat `old()`.

**4. Jika validasi berhasil**

- Controller memanggil `Product::create($request->validated())`.
- Model mengecek `$fillable`, lalu Eloquent menjalankan `INSERT` ke tabel `products`.
- Controller mengembalikan `redirect()->route('products.index')->with('success', ...)`.

**5. Menampilkan data**

- Browser membuka `GET /products`, yaitu `ProductController@index`.
- Controller mengambil produk lewat Eloquent (`search`, filter, `orderBy`, `paginate`) dan mengirimnya ke view.
- View menampilkan tabel dan pesan sukses dari session.

## Contoh Alur Stock Out

`POST /stock/out` → `StockMovementRequest` (validasi) → `StockController@storeOut` → `StockService::record()` → dalam satu transaction: kunci baris produk, cek stok cukup, kurangi stok, catat ke `stock_movements` → redirect ke Stock History.

Jika stok tidak cukup, `StockService` melempar `ValidationException` dan transaction dibatalkan. Stok tidak berubah dan tidak ada histori yang tercatat.

## Relationship Eloquent

| Model | Relasi | Arti |
| --- | --- | --- |
| `Category` | `hasMany(Product)` | satu kategori punya banyak produk |
| `Product` | `belongsTo(Category)` | satu produk milik satu kategori |
| `Product` dan `Supplier` | `belongsToMany` lewat `product_supplier` | banyak ke banyak dengan harga supplier |
| `Product` | `hasMany(StockMovement)` | satu produk punya banyak transaksi |
| `StockMovement` | `belongsTo(Product, User, Supplier)` | transaksi mencatat produk, pelaku, dan pemasok |

## Blade Layout dan Component

- `layouts/app.blade.php`: kerangka halaman (sidebar, topbar, pesan flash). Halaman lain memakai `@extends` dan `@section`.
- `components/alert.blade.php`: kotak pesan (`<x-alert>`).
- `components/field.blade.php`: label, input, dan pesan error dalam satu komponen (`<x-field>`).
- `components/stock-badge.blade.php`: label status stok (`<x-stock-badge>`).
- Semua output memakai `{{ }}` sehingga otomatis di-escape.
