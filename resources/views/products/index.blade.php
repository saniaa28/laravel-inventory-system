@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">MASTER DATA</span>
        <h1>Products</h1>
        <p>Manage inventory products and stock information.</p>
    </div>
    @if(auth()->user()->isAdmin())
        <a class="btn primary" href="{{ route('products.create') }}">+ Add Product</a>
    @endif
</div>

{{-- Search, filter, dan sorting dikirim lewat query string (GET) --}}
<form class="filters card" method="GET" action="{{ route('products.index') }}">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by product name or SKU...">
    <select name="category_id">
        <option value="">All categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    <select name="status">
        <option value="">All status</option>
        <option value="in" @selected(request('status') === 'in')>In Stock</option>
        <option value="low" @selected(request('status') === 'low')>Low Stock</option>
        <option value="out" @selected(request('status') === 'out')>Out of Stock</option>
    </select>
    <select name="sort">
        @foreach(['name' => 'Name', 'sku' => 'SKU', 'price' => 'Price', 'stock' => 'Stock'] as $value => $label)
            <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="dir">
        <option value="asc" @selected($dir === 'asc')>ASC</option>
        <option value="desc" @selected($dir === 'desc')>DESC</option>
    </select>
    <button type="submit" class="btn secondary">Filter</button>
    <a class="btn ghost" href="{{ route('products.index') }}">Reset</a>
</form>

<section class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>SKU</th><th>Product Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Minimum</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td><code>{{ $product->sku }}</code></td>
                    <td><a href="{{ route('products.show', $product) }}"><b>{{ $product->name }}</b></a></td>
                    <td>{{ $product->category->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->minimum_stock }}</td>
                    <td><x-stock-badge :product="$product" /></td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center;">
                            <a class="btn tiny" href="{{ route('products.show', $product) }}">View</a>
                            @if(auth()->user()->isAdmin())
                                <a class="btn tiny" href="{{ route('products.edit', $product) }}">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn tiny danger-btn">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="empty">No products found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
    <div class="pager-info">Page {{ $products->currentPage() }} / {{ $products->lastPage() }} · {{ $products->total() }} products</div>
</section>
@endsection
