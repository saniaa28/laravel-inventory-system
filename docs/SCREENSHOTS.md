# Screenshot dan Alur Demo

Semua gambar ada di folder `docs/screenshots/`. Setiap gambar memperlihatkan alamat browser dan akun yang sedang login.

## A. Terminal

### 01. Daftar route (`php artisan route:list --except-vendor`)
![Route list bagian 1](screenshots/01-route-list-1.png)
![Route list bagian 2 (37 route)](screenshots/01-route-list-2.png)

### 02. Migration dan seeder (`php artisan migrate:fresh --seed`)
![Migration dan seeder bagian 1](screenshots/02-migrate-fresh-seed-1.png)
![Seeder bagian 2](screenshots/02-migrate-fresh-seed-2.png)

### 03. Test (`php artisan test`, 20 test lulus)
![Test bagian 1](screenshots/03-php-artisan-test-1.png)
![Test bagian 2 (20 passed)](screenshots/03-php-artisan-test-2.png)

## B. CRUD (login sebagai admin)

### 04. Daftar produk dengan pagination
![Daftar produk](screenshots/04-products-index.png)

### 05. Search dan filter (query string terlihat di alamat)
![Search produk](screenshots/05-products-search-filter.png)

### 06. Halaman 2 (query string tetap terbawa)
![Produk halaman 2](screenshots/06-products-page2.png)

### 07. Form tambah produk
![Form tambah produk](screenshots/07-products-create.png)

### 08. Form edit produk (stok terkunci, perubahan stok lewat Stock In/Out)
![Form edit produk](screenshots/08-products-edit.png)

### 09. Detail produk: informasi, supplier, dan transaksi
![Detail produk](screenshots/09-products-show.png)
![Transaksi stok terakhir](screenshots/09-products-show-transaksi.png)

### 10. Hapus produk tanpa histori
![Produk berhasil dihapus](screenshots/10-products-delete.png)

### 11. Kategori
![Daftar kategori](screenshots/11-categories.png)

### 12. Supplier (tab Active dan Archived)
![Daftar supplier](screenshots/12-suppliers.png)

## C. Validasi dan aturan bisnis

### 13. SKU duplikat
![SKU must be unique](screenshots/13-validasi-sku-duplikat.png)

### 14. Stok awal nol
![Initial stock must be greater than zero](screenshots/14-validasi-stok-awal-nol.png)

### 15. Stock Out melebihi stok
![Insufficient stock](screenshots/15-stock-out-melebihi-stok.png)

### 16. Hapus produk yang punya histori
![Produk tidak bisa dihapus](screenshots/16-hapus-produk-berhistori.png)

### 17. Hapus kategori yang masih dipakai
![Kategori tidak bisa dihapus](screenshots/17-hapus-kategori-terpakai.png)

## D. Tampilan Blade

### 18. Login
![Halaman login](screenshots/18-login.png)

### 19. Dashboard
![Dashboard admin](screenshots/19-dashboard.png)

### 20. Stock In berhasil, tampil di Stock History
![Stock History setelah Stock In](screenshots/20-stock-in-berhasil.png)

### 21. Laporan (SUM dan AVG per produk)
![Laporan inventaris](screenshots/21-reports.png)

### 22. Manajemen user
![Daftar user](screenshots/22-users.png)

## E. Akses terlarang (login sebagai staff)

### 23. Dashboard staff, menu tanpa Users
![Dashboard staff](screenshots/23-staff-dashboard.png)

### 24. Staff membuka `/users` dan mendapat 403
![403 Forbidden](screenshots/24-staff-403.png)

## Alur demo (satu alur lengkap)

Satu alur utuh: membuka form, validasi, simpan, redirect, lalu data tampil.

**1. Membuka form** (`GET /products/create`)

![Form tambah produk](screenshots/07-products-create.png)

**2. Validasi gagal.** SKU `INV-KEY-001` sudah ada, sehingga muncul "SKU must be unique." dan isian lama tetap terisi.

![Validasi SKU duplikat](screenshots/13-validasi-sku-duplikat.png)

**3. Simpan dan redirect.** SKU diganti menjadi `INV-DEMO-001` lalu disimpan. Aplikasi mengarahkan ke `/products` dengan pesan sukses, dan produk baru langsung tampil di daftar.

![Produk berhasil ditambahkan](screenshots/25-demo-simpan-berhasil.png)

Alur kode: Route, `StoreProductRequest`, `ProductController@store`, model `Product`, tabel `products`, lalu redirect ke `products.index`. Penjelasan lengkapnya ada di [`mvc-architecture.md`](mvc-architecture.md).
