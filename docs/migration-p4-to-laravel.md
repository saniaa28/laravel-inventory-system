# Migrasi INVORA: PHP Native (P4) ke Laravel (P5)

## Pemetaan

| P4 PHP Native | P5 Laravel |
| --- | --- |
| `database/schema.sql` | `database/migrations/` dan `database/seeders/` |
| Form PHP manual dan validasi `if` | Blade form dan Form Request (`@error`, `old()`) |
| Query PDO | Eloquent |
| File CRUD terpisah (`create.php`, `edit.php`, `delete.php`) | Resource controller |
| Session manual dan `require_login()` | `Auth` Laravel dan middleware `auth` |
| `require_role('admin')` | middleware `role:admin` |
| `views/header.php` dan `footer.php` | Blade layout `layouts/app.blade.php` |
| `helpers/escape.php` (`e()`) | `{{ }}` pada Blade |
| `LIMIT` dan `OFFSET` manual | `paginate()->withQueryString()` |
| `beginTransaction()` dan `FOR UPDATE` | `DB::transaction()` dan `lockForUpdate()` |

## Business Rule yang Tetap Berjalan

| No | Aturan | Lokasi di Laravel | Dibuktikan oleh test |
| --- | --- | --- | --- |
| 1 | SKU harus unik | `StoreProductRequest`, `UpdateProductRequest`, kolom `sku` unique | `test_product_validation_rules` |
| 2 | Harga dan stok tidak boleh negatif | Form Request, kolom stok `unsignedInteger` | `test_product_validation_rules` |
| 3 | Stok awal produk baru harus lebih dari nol | `StoreProductRequest` | `test_product_validation_rules` |
| 4 | Stok tidak bisa diedit dari halaman Edit Product | `UpdateProductRequest` tidak memuat stok | `test_update_does_not_change_stock` |
| 5 | Produk yang punya riwayat stok tidak boleh dihapus | `ProductController@destroy`, `restrictOnDelete` | `test_product_with_history_cannot_be_deleted` |
| 6 | Quantity transaksi harus lebih dari nol | `StockMovementRequest`, `StockService` | `test_stock_quantity_must_be_positive` |
| 7 | Stock Out tidak boleh melebihi stok | `StockService` | `test_stock_out_cannot_exceed_stock` |
| 8 | Setiap Stock In dan Out tercatat di histori | `StockService`, tabel `stock_movements` | `test_stock_in_increases_stock_and_records_history` |
| 9 | Update stok dan histori dalam satu transaction | `DB::transaction()` di `StockService` | `test_stock_out_cannot_exceed_stock` |
| 10 | Kategori yang dipakai produk tidak boleh dihapus | `CategoryController@destroy`, `restrictOnDelete` | `test_category_rules` |
| 11 | Supplier diarsipkan, bukan dihapus, dan bisa dipulihkan | `SupplierController@archive` dan `restore`, kolom `is_active` | `test_supplier_archive_and_restore` |
| 12 | Supplier arsip tidak muncul di dropdown Stock In | `Supplier::active()` | `test_supplier_archive_and_restore` |
| 13 | Halaman admin menolak staff dengan 403 | middleware `EnsureRole` | `test_staff_gets_403_on_admin_pages` |
| 14 | User tidak bisa menghapus akunnya sendiri dan akun yang punya riwayat | `UserController@destroy` | `test_user_delete_rules` |
| 15 | Password di-hash | cast `hashed` pada model `User` | `test_login_success_and_failure` |

## Perbedaan dari P4

- Hak akses dibuat lebih ketat sesuai pembagian peran: staff hanya melihat produk dan mencatat transaksi, sedangkan kategori, supplier, user, serta tambah, edit, hapus produk khusus admin.
- Jumlah produk pada Categories dan Suppliers selalu dihitung otomatis dengan `withCount('products')`. Angka manual yang ada di P4 tidak dibawa karena bisa membuat tampilan berbeda dari data sebenarnya.
- Stok demo diisi lewat `StockMovementSeeder` memakai `StockService`, sehingga stok awal selalu cocok dengan histori transaksi.
