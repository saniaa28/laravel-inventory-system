<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    // READ: daftar produk dengan search, filter, sorting, dan pagination
    public function index(Request $request): View
    {
        // Kolom pengurutan hanya boleh dari daftar ini (allowlist)
        $sort = in_array($request->sort, ['name', 'sku', 'price', 'stock'], true) ? $request->sort : 'name';
        $dir = strtolower((string) $request->dir) === 'desc' ? 'desc' : 'asc';

        $products = Product::with('category')
            ->search($request->q)
            ->when($request->category_id, fn ($q, $id) => $q->where('category_id', $id))
            ->status($request->status)
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString(); // supaya search dan filter tidak hilang saat pindah halaman

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'sort', 'dir'));
    }

    public function create(): View
    {
        return view('products.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    // STORE: data sudah divalidasi oleh StoreProductRequest sebelum masuk ke sini
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'suppliers']);

        return view('products.show', [
            'product'   => $product,
            'movements' => $product->movements()->with(['user', 'supplier'])->latest('id')->limit(10)->get(),
            'available' => Supplier::active()->whereNotIn('id', $product->suppliers->pluck('id'))->orderBy('supplier_name')->get(),
        ]);
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    // UPDATE: stok sengaja tidak ikut diubah di sini
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Aturan bisnis: produk yang punya riwayat stok tidak boleh dihapus
        if ($product->movements()->exists()) {
            return back()->with('error', 'Product cannot be deleted because it already has stock history.');
        }

        try {
            $product->delete();
        } catch (QueryException) {
            return back()->with('error', 'Product could not be deleted.');
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // Menghubungkan produk ke supplier beserta harga belinya
    public function attachSupplier(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'supplier_id'    => ['required', 'integer', 'exists:suppliers,id'],
            'supplier_price' => ['required', 'numeric', 'min:0'],
        ], [
            'supplier_price.min' => 'Supplier price must be non-negative.',
        ]);

        $product->suppliers()->syncWithoutDetaching([
            $data['supplier_id'] => ['supplier_price' => $data['supplier_price']],
        ]);

        return back()->with('success', 'Supplier linked to product.');
    }

    public function detachSupplier(Product $product, Supplier $supplier): RedirectResponse
    {
        $product->suppliers()->detach($supplier->id);

        return back()->with('success', 'Supplier removed from product.');
    }
}
