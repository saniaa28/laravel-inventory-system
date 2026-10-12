<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalProducts = Product::count();
        $inventoryValue = (float) Product::sum(DB::raw('stock * price'));
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalIn = (int) StockMovement::where('type', 'IN')->sum('quantity');
        $totalOut = (int) StockMovement::where('type', 'OUT')->sum('quantity');

        $lowProducts = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        // Pergerakan stok 7 hari terakhir, dikelompokkan per tanggal
        $chart = StockMovement::selectRaw(
                "movement_date as day,
                 SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END) as stock_in,
                 SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END) as stock_out"
            )
            ->whereDate('movement_date', '>=', now()->subDays(6)->toDateString())
            ->groupBy('movement_date')
            ->orderBy('movement_date')
            ->get();

        $recent = StockMovement::with(['product', 'user'])->latest('id')->limit(5)->get();

        return view('dashboard.index', compact(
            'totalProducts', 'inventoryValue', 'lowStockCount', 'totalIn', 'totalOut',
            'lowProducts', 'chart', 'recent'
        ));
    }
}
