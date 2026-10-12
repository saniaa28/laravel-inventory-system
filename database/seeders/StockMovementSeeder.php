<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(StockService $stock): void
    {
        $admin = User::where('username', 'admin')->value('id');
        $staff = User::where('username', 'staff')->value('id');

        $id = fn (string $sku) => Product::where('sku', $sku)->value('id');
        $supplier = fn (string $name) => Supplier::where('supplier_name', $name)->value('id');
        $day = fn (int $ago) => now()->subDays($ago)->toDateString();

        // Setiap transaksi lewat StockService, jadi stok produk ikut berubah dengan benar
        $stock->record($id('INV-MOU-001'), 'IN', 50, $day(6), 'Initial demo stock', $admin, $supplier('PT Tech Supply'));
        $stock->record($id('INV-MOU-001'), 'OUT', 15, $day(5), 'Demo sale', $staff);
        $stock->record($id('INV-KEY-001'), 'IN', 30, $day(4), 'Keyboard replenishment', $admin, $supplier('PT Tech Supply'));
        $stock->record($id('INV-KEY-001'), 'OUT', 8, $day(3), 'Demo sale', $staff);
        $stock->record($id('INV-MON-001'), 'IN', 10, $day(2), 'Monitor replenishment', $admin, $supplier('PT Tech Supply'));
        $stock->record($id('INV-CHA-001'), 'IN', 20, $day(1), 'Chair replenishment', $staff, $supplier('CV Office Nusantara'));
        $stock->record($id('INV-CHA-001'), 'OUT', 2, $day(0), 'Demo sale', $staff);
        $stock->record($id('INV-MON-001'), 'OUT', 3, $day(0), 'Demo monitor sale', $staff);
    }
}
