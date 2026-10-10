<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. READ: Menampilkan daftar barang
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // 2. CREATE: Menampilkan form tambah barang
    public function create()
    {
        return view('products.create');
    }

    // 3. STORE: Menyimpan data baru ke database
    public function store(Request $request)
    {
        Product::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price
        ]);

        return redirect('/products');
    }

    // 4. EDIT: Menampilkan form edit barang
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // 5. UPDATE: Menyimpan perubahan data ke database
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
            'price' => $request->price
        ]);

        return redirect('/products');
    }

    // 6. DESTROY: Menghapus data dari database
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect('/products');
    }
}