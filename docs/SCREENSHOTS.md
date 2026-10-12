# Panduan Screenshot dan Alur Demo

Simpan semua gambar di folder `docs/screenshots/` dengan nama sesuai daftar. Pastikan alamat browser dan akun yang login ikut terlihat.

## A. Screenshot Route (terminal)

| Nama file | Perintah | Yang terlihat |
| --- | --- | --- |
| `01-route-list.png` | `php artisan route:list --except-vendor` | route resource `products.*`, `categories.*`, dan seterusnya |
| `02-migrate-fresh-seed.png` | `php artisan migrate:fresh --seed` | semua migration dan seeder berstatus DONE |
| `03-php-artisan-test.png` | `php artisan test` | semua test PASS |

## B. Screenshot CRUD (login sebagai admin)

| Nama file | Halaman | Yang dilakukan |
| --- | --- | --- |
| `04-products-index.png` | `/products` | daftar produk dengan pagination |
| `05-products-search-filter.png` | `/products` | isi search atau filter, tampilkan alamat dengan query string |
| `06-products-page2.png` | `/products?page=2` | halaman 2, query string tetap terbawa |
| `07-products-create.png` | `/products/create` | form tambah produk |
| `08-products-edit.png` | `/products/{id}/edit` | form edit, kolom stok terkunci |
| `09-products-show.png` | `/products/{id}` | detail produk, supplier, dan transaksi |
| `10-products-delete.png` | `/products` | hapus produk tanpa histori, tampilkan pesan sukses |
| `11-categories.png` | `/categories` | daftar dan form kategori |
| `12-suppliers.png` | `/suppliers` | daftar supplier, tab Active dan Archived |

## C. Screenshot Validasi dan Aturan Bisnis

| Nama file | Cara membuat | Pesan yang muncul |
| --- | --- | --- |
| `13-validasi-sku-duplikat.png` | tambah produk dengan SKU `INV-KEY-001` | SKU must be unique. |
| `14-validasi-stok-awal-nol.png` | tambah produk dengan Initial Stock `0` | Initial stock must be greater than zero. |
| `15-stock-out-melebihi-stok.png` | Stock Out Monitor 24" dengan Quantity `999` | Insufficient stock. Available stock: 7 units. |
| `16-hapus-produk-berhistori.png` | hapus Wireless Mouse | Product cannot be deleted because it already has stock history. |
| `17-hapus-kategori-terpakai.png` | hapus kategori Electronics | Category cannot be deleted because products still use it. |

## D. Screenshot Blade

| Nama file | Halaman |
| --- | --- |
| `18-login.png` | `/login` |
| `19-dashboard.png` | `/dashboard` |
| `20-stock-in-berhasil.png` | Stock History setelah Stock In |
| `21-reports.png` | `/reports` |
| `22-users.png` | `/users` |

## E. Screenshot Akses Terlarang

Login sebagai `staff` (password `staff123`), lalu buka `http://127.0.0.1:8000/users`.

| Nama file | Yang terlihat |
| --- | --- |
| `23-staff-dashboard.png` | dashboard dengan akun staff dan menu tanpa Users |
| `24-staff-403.png` | halaman 403 Forbidden |

## Alur Demo (satu alur lengkap)

Slide 48 meminta satu alur: membuka form, validasi, simpan, redirect, tampilkan data.

1. Login sebagai admin, buka **Products**, klik **+ Add Product**. Ambil screenshot form kosong.
2. Isi SKU `INV-KEY-001` (sudah ada), klik **Save Product**. Muncul error "SKU must be unique." dan isian lama tetap terisi. Ambil screenshot.
3. Ganti SKU menjadi `INV-DEMO-001`, klik **Save Product**.
4. Aplikasi mengarahkan ke daftar produk dengan pesan "Product added successfully." Produk baru tampil. Ambil screenshot.

Saat presentasi, jelaskan sambil menunjuk alurnya: Route → `StoreProductRequest` → `ProductController@store` → model `Product` → tabel `products` → redirect ke `products.index`.
