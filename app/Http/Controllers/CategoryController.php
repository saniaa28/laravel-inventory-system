<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('categories.index', [
            'categories' => Category::withCount('products')->orderBy('name')->get(),
            'editing'    => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ], ['name.unique' => 'Category name already exists.']);

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    // Form edit memakai halaman yang sama dengan daftar kategori
    public function edit(Category $category): View
    {
        return view('categories.index', [
            'categories' => Category::withCount('products')->orderBy('name')->get(),
            'editing'    => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
        ], ['name.unique' => 'This category name is already used.']);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Aturan bisnis: kategori yang masih dipakai produk tidak boleh dihapus
        if ($category->products()->exists()) {
            return back()->with('error', 'Category cannot be deleted because products still use it.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
