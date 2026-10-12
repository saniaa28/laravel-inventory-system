@extends('layouts.app')
@section('title', 'Stock History')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">INVENTORY</span>
        <h1>Stock History</h1>
        <p>Every stock change is recorded for auditability.</p>
    </div>
</div>

<form class="filters card" method="GET" action="{{ route('stock.history') }}">
    <select name="type">
        <option value="">All types</option>
        <option value="IN" @selected(request('type') === 'IN')>Stock In</option>
        <option value="OUT" @selected(request('type') === 'OUT')>Stock Out</option>
    </select>
    <select name="product_id">
        <option value="">All products</option>
        @foreach($products as $p)
            <option value="{{ $p->id }}" @selected(request('product_id') == $p->id)>{{ $p->name }}</option>
        @endforeach
    </select>
    <input type="date" name="from" value="{{ request('from') }}">
    <input type="date" name="to" value="{{ request('to') }}">
    <button type="submit" class="btn secondary">Filter</button>
    <a class="btn ghost" href="{{ route('stock.history') }}">Reset</a>
</form>

<section class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Product</th><th>Type</th><th>Quantity</th><th>User</th><th>Supplier</th><th>Notes</th></tr></thead>
            <tbody>
            @forelse($movements as $m)
                <tr>
                    <td>{{ $m->movement_date->format('d M Y') }}</td>
                    <td><b>{{ $m->product->name }}</b><small class="block">{{ $m->product->sku }}</small></td>
                    <td><span class="badge {{ $m->type === 'IN' ? 'success' : 'danger' }}">{{ $m->type }}</span></td>
                    <td>{{ $m->quantity }}</td>
                    <td>{{ $m->user->username ?? $m->user->name }}</td>
                    <td>{{ $m->supplier->supplier_name ?? '—' }}</td>
                    <td>{{ $m->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="empty">No stock movements found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $movements->links() }}
</section>
@endsection
