# INVORA Laravel

Inventory Management System INVORA yang dipindahkan dari PHP native (Pertemuan 4) ke Laravel (Pertemuan 5).
Framework berubah, tetapi aturan bisnis stok dari Pertemuan 4 tetap berjalan.

## Fitur

- Login dengan dua peran: **admin** dan **staff**
- Dashboard (total produk, nilai inventaris, stok rendah, grafik 7 hari)
- CRUD Produk dengan search, filter kategori dan status, sorting, dan pagination
- CRUD Kategori dan Supplier (supplier diarsipkan, bukan dihapus, dan bisa dipulihkan)
- Hubungan produk dengan supplier beserta harga beli
- Stock In dan Stock Out memakai database transaction dan row locking
- Stock History dengan filter jenis, produk, dan tanggal
- Laporan inventaris dengan SUM dan AVG per produk
- Manajemen user (khusus admin)

## Kebutuhan

| Perangkat | Versi |
| --- | --- |
| PHP | 8.2 atau lebih baru |
| Composer | 2.x |
| MySQL atau MariaDB | dari XAMPP juga bisa |

## Cara Instalasi

1. Ambil project dan masuk ke foldernya.

   ```bash
   git clone https://github.com/saniaa28/laravel-inventory-system.git
   cd laravel-inventory-system
   ```

2. Pasang dependensi.

   ```bash
   composer install
   ```

3. Salin file pengaturan lalu buat application key.

   ```bash
   copy .env.example .env        # di Linux atau Mac: cp .env.example .env
   php artisan key:generate
   ```

4. Nyalakan **MySQL** di XAMPP, lalu buat database kosong bernama `inventory_db` lewat phpMyAdmin.
   Pengaturan koneksi sudah ada di `.env` (`DB_DATABASE=inventory_db`, user `root`, password kosong).

5. Buat semua tabel dan isi data contoh.

   ```bash
   php artisan migrate:fresh --seed
   ```

   Peringatan: perintah ini menghapus semua tabel di `inventory_db` lalu membuatnya ulang.

6. Jalankan aplikasi.

   ```bash
   php artisan serve
   ```

   Buka http://127.0.0.1:8000

## Akun Demo

| Peran | Username | Password |
| --- | --- | --- |
| Admin | `admin` | `admin123` |
| Staff | `staff` | `staff123` |

## Hak Akses

| Fitur | Admin | Staff |
| --- | :---: | :---: |
| Lihat produk, detail produk | ✔ | ✔ |
| Tambah, edit, hapus produk | ✔ | ✘ |
| Kategori, supplier, user | ✔ | ✘ |
| Stock In, Stock Out, History | ✔ | ✔ |
| Dashboard dan laporan | ✔ | ✔ |

Staff yang membuka halaman admin akan mendapat halaman **403 Forbidden**.

## Menjalankan Test

```bash
php artisan test
```

Test memakai database SQLite di memori, jadi tidak menyentuh data di `inventory_db`.

## Perintah Artisan yang Berguna

```bash
php artisan route:list --except-vendor   # daftar route
php artisan migrate:status               # status migration
php artisan migrate:fresh --seed         # reset database dan isi ulang
```

## Struktur Penting

```
app/
├── Http/Controllers/     ProductController, CategoryController, SupplierController,
│                         StockController, ReportController, UserController, Auth/LoginController
├── Http/Middleware/      EnsureRole (pembatas role)
├── Http/Requests/        StoreProductRequest, UpdateProductRequest, StockMovementRequest
├── Models/               Product, Category, Supplier, StockMovement, User
└── Services/             StockService (aturan bisnis stok)
database/
├── migrations/           struktur tabel
├── factories/            pembuat data uji
└── seeders/              data contoh
resources/views/          layouts, components, dan halaman Blade
routes/web.php            semua route
tests/Feature/            test aturan bisnis
docs/                     dokumentasi (lihat di bawah)
```

## Dokumentasi

- [`docs/mvc-architecture.md`](docs/mvc-architecture.md) penjelasan arsitektur MVC dan alur request sampai response
- [`docs/migration-p4-to-laravel.md`](docs/migration-p4-to-laravel.md) pemetaan PHP native ke Laravel dan daftar business rule
- [`docs/SCREENSHOTS.md`](docs/SCREENSHOTS.md) daftar screenshot dan alur demo
- [`docs/route-list.txt`](docs/route-list.txt) hasil `php artisan route:list`
