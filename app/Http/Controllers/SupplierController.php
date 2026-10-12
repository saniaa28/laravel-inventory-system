<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $view = $request->query('view') === 'archived' ? 'archived' : 'active';

        return view('suppliers.index', [
            'suppliers' => Supplier::withCount('products')
                ->where('is_active', $view === 'active')
                ->orderBy('supplier_name')
                ->get(),
            'view'      => $view,
            'editing'   => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Supplier::create($this->validated($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully.');
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.index', [
            'suppliers' => Supplier::withCount('products')->where('is_active', true)->orderBy('supplier_name')->get(),
            'view'      => 'active',
            'editing'   => $supplier,
        ]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($this->validated($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    // Supplier tidak dihapus, tapi diarsipkan supaya riwayat tetap utuh
    public function destroy(Supplier $supplier): RedirectResponse
    {
        return $this->archive($supplier);
    }

    public function archive(Supplier $supplier): RedirectResponse
    {
        $supplier->update(['is_active' => false]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier archived successfully.');
    }

    public function restore(Supplier $supplier): RedirectResponse
    {
        $supplier->update(['is_active' => true]);

        return redirect()->route('suppliers.index', ['view' => 'archived'])->with('success', 'Supplier restored successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'supplier_name'  => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'email'          => ['nullable', 'email', 'max:150'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'address'        => ['nullable', 'string', 'max:255'],
        ]);
    }
}
