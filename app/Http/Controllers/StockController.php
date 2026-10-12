<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function createIn(): View
    {
        return view('stock.create', [
            'type'      => 'IN',
            'products'  => Product::orderBy('name')->get(),
            'suppliers' => Supplier::active()->orderBy('supplier_name')->get(),
        ]);
    }

    public function storeIn(StockMovementRequest $request, StockService $stock): RedirectResponse
    {
        return $this->record($request, $stock, 'IN');
    }

    public function createOut(): View
    {
        return view('stock.create', [
            'type'      => 'OUT',
            'products'  => Product::orderBy('name')->get(),
            'suppliers' => collect(),
        ]);
    }

    public function storeOut(StockMovementRequest $request, StockService $stock): RedirectResponse
    {
        return $this->record($request, $stock, 'OUT');
    }

    public function history(Request $request): View
    {
        $movements = StockMovement::with(['product', 'user', 'supplier'])
            ->when($request->type, fn ($q, $t) => $q->where('type', $t))
            ->when($request->product_id, fn ($q, $id) => $q->where('product_id', $id))
            ->when($request->from, fn ($q, $d) => $q->whereDate('movement_date', '>=', $d))
            ->when($request->to, fn ($q, $d) => $q->whereDate('movement_date', '<=', $d))
            ->latest('movement_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('stock.history', [
            'movements' => $movements,
            'products'  => Product::orderBy('name')->get(),
        ]);
    }

    private function record(StockMovementRequest $request, StockService $stock, string $type): RedirectResponse
    {
        $data = $request->validated();

        // Kalau stok tidak cukup, StockService melempar ValidationException
        // dan Laravel otomatis mengembalikan pengguna ke form dengan pesan error.
        $stock->record(
            (int) $data['product_id'],
            $type,
            (int) $data['quantity'],
            $data['date'],
            $data['notes'] ?? null,
            $request->user()->id,
            isset($data['supplier_id']) ? (int) $data['supplier_id'] : null,
        );

        return redirect()->route('stock.history')->with('success', 'Stock successfully updated.');
    }
}
