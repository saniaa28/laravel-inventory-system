@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">PRODUCT DETAIL</span>
        <h1>{{ $product->name }}</h1>
        <p><code>{{ $product->sku }}</code></p>
    </div>
    <a class="btn ghost" href="{{ route('products.index') }}">Back to Products</a>
</div>

<div class="grid-2">
    <section class="card">
        <div class="card-head"><h2>Information</h2><x-stock-badge :product="$product" /></div>
        <div class="detail-grid">
            <div><span>Category</span><b>{{ $product->category->name }}</b></div>
            <div><span>Price</span><b>Rp {{ number_format($product->price, 0, ',', '.') }}</b></div>
            <div><span>Current Stock</span><b>{{ $product->stock }}</b></div>
            <div><span>Minimum Stock</span><b>{{ $product->minimum_stock }}</b></div>
            <div class="full-field"><span>Description</span><b>{{ $product->description ?: '-' }}</b></div>
        </div>
    </section>

    <section class="card">
        <div class="card-head"><h2>Suppliers</h2></div>
        <div class="list">
            @forelse($product->suppliers as $s)
                <div class="list-row">
                    <div><b>{{ $s->supplier_name }}</b><small>Rp {{ number_format($s->pivot->supplier_price, 0, ',', '.') }}</small></div>
                    @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('products.suppliers.destroy', [$product, $s]) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn tiny danger-btn">Remove</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="empty">No suppliers linked.</div>
            @endforelse
        </div>

        @if(auth()->user()->isAdmin() && $available->isNotEmpty())
            <form method="POST" action="{{ route('products.suppliers.store', $product) }}" class="form-grid two" style="margin-top:14px">
                @csrf
                <x-field label="Supplier" name="supplier_id" type="select" required>
                    <option value="">Choose supplier</option>
                    @foreach($available as $s)
                        <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->supplier_name }}</option>
                    @endforeach
                </x-field>
                <x-field label="Supplier Price" name="supplier_price" type="number" min="0" step="0.01" required />
                <div class="form-actions"><button type="submit" class="btn primary">Link Supplier</button></div>
            </form>
        @endif
    </section>
</div>

<section class="card">
    <div class="card-head"><h2>Latest Stock Movements</h2><a href="{{ route('stock.history', ['product_id' => $product->id]) }}">View all</a></div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Type</th><th>Quantity</th><th>User</th><th>Supplier</th><th>Notes</th></tr></thead>
            <tbody>
            @forelse($movements as $m)
                <tr>
                    <td>{{ $m->movement_date->format('d M Y') }}</td>
                    <td><span class="badge {{ $m->type === 'IN' ? 'success' : 'danger' }}">{{ $m->type }}</span></td>
                    <td>{{ $m->quantity }}</td>
                    <td>{{ $m->user->username ?? $m->user->name }}</td>
                    <td>{{ $m->supplier->supplier_name ?? '-' }}</td>
                    <td>{{ $m->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No stock movements yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
