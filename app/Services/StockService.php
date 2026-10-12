<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Semua perubahan stok (Stock In dan Stock Out) lewat class ini,
 * supaya aturan bisnis dari P4 hanya ada di satu tempat:
 * 1. quantity harus lebih dari nol
 * 2. Stock Out tidak boleh melebihi stok
 * 3. update stok dan pencatatan histori dalam satu transaction
 */
class StockService
{
    public function record(
        int $productId,
        string $type,
        int $quantity,
        string $date,
        ?string $notes,
        int $userId,
        ?int $supplierId = null
    ): StockMovement {
        return DB::transaction(function () use ($productId, $type, $quantity, $date, $notes, $userId, $supplierId) {
            // lockForUpdate: kunci baris produk sampai transaction selesai
            $product = Product::whereKey($productId)->lockForUpdate()->firstOrFail();

            if ($quantity < 1) {
                throw ValidationException::withMessages([
                    'quantity' => 'Quantity must be greater than zero.',
                ]);
            }

            if ($type === 'OUT' && $quantity > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => "Insufficient stock. Available stock: {$product->stock} units.",
                ]);
            }

            $product->stock = $type === 'IN'
                ? $product->stock + $quantity
                : $product->stock - $quantity;
            $product->save();

            return StockMovement::create([
                'product_id'    => $product->id,
                'user_id'       => $userId,
                'supplier_id'   => $type === 'IN' ? $supplierId : null,
                'type'          => $type,
                'quantity'      => $quantity,
                'movement_date' => $date,
                'notes'         => $notes ?: null,
            ]);
        });
    }
}
