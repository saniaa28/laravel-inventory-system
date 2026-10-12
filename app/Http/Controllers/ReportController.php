<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        // Agregat per produk: SUM dan AVG dihitung oleh database lewat Eloquent
        $rows = Product::query()
            ->withSum(['movements as total_in' => fn ($q) => $q->where('type', 'IN')], 'quantity')
            ->withSum(['movements as total_out' => fn ($q) => $q->where('type', 'OUT')], 'quantity')
            ->withAvg(['movements as avg_in' => fn ($q) => $q->where('type', 'IN')], 'quantity')
            ->orderBy('name')
            ->get();

        return view('reports.index', [
            'rows'     => $rows,
            'totalIn'  => (int) StockMovement::where('type', 'IN')->sum('quantity'),
            'totalOut' => (int) StockMovement::where('type', 'OUT')->sum('quantity'),
        ]);
    }
}
