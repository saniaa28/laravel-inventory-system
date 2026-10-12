<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cat = Category::pluck('id', 'name');
        $sup = Supplier::pluck('id', 'supplier_name');

        // 5 produk demo dari P4. Stok dimulai 0, lalu diisi lewat StockMovementSeeder
        // supaya stok selalu cocok dengan riwayat transaksinya.
        $demo = [
            ['Electronics', 'INV-MOU-001', 'Wireless Mouse', 175000, 10, ['PT Tech Supply' => 145000, 'Digital Partner' => 150000]],
            ['Electronics', 'INV-KEY-001', 'Mechanical Keyboard', 650000, 8, ['PT Tech Supply' => 560000, 'Digital Partner' => 575000]],
            ['Electronics', 'INV-MON-001', 'Monitor 24"', 1850000, 8, ['PT Tech Supply' => 1650000]],
            ['Accessories', 'INV-USB-001', 'USB Hub', 125000, 5, ['PT Tech Supply' => 95000, 'Digital Partner' => 98000]],
            ['Furniture', 'INV-CHA-001', 'Office Chair', 1250000, 5, ['CV Office Nusantara' => 1100000]],
        ];

        foreach ($demo as [$category, $sku, $name, $price, $min, $suppliers]) {
            $product = Product::create([
                'category_id'   => $cat[$category],
                'sku'           => $sku,
                'name'          => $name,
                'price'         => $price,
                'stock'         => 0,
                'minimum_stock' => $min,
            ]);

            foreach ($suppliers as $supplierName => $supplierPrice) {
                $product->suppliers()->attach($sup[$supplierName], ['supplier_price' => $supplierPrice]);
            }
        }

        // 14 produk tambahan dari factory, supaya pagination (10 per halaman) bisa dicoba
        Product::factory()->count(14)->recycle(Category::all())->create();
    }
}
