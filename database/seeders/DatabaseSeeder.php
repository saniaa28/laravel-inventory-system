<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Urutan penting: user dan kategori dulu, baru produk, lalu transaksi stok
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            StockMovementSeeder::class,
        ]);
    }
}
