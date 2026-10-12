@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">OVERVIEW</span>
        <h1>Good day, {{ ucfirst(auth()->user()->username ?? auth()->user()->name) }} 👋</h1>
        <p>Here's an overview of your inventory today.</p>
    </div>
    <a class="btn primary" href="{{ route('stock.in') }}">+ Stock In</a>
</div>

<div class="kpis">
    <div class="kpi"><span>Total Products</span><strong>{{ number_format($totalProducts) }}</strong><small>Products in catalog</small></div>
    <div class="kpi"><span>Inventory Value</span><strong>Rp {{ number_format($inventoryValue, 0, ',', '.') }}</strong><small>Current stock × price</small></div>
    <div class="kpi"><span>Low Stock</span><strong>{{ $lowStockCount }}</strong><small>At or below minimum</small></div>
    <div class="kpi"><span>Stock Movement</span><strong>+{{ number_format($totalIn) }} / -{{ number_format($totalOut) }}</strong><small>Total IN / OUT</small></div>
</div>

<div class="grid-2">
    <section class="card">
        <div class="card-head"><div><h2>Inventory Activity</h2><p>Stock movement for the last 7 days.</p></div></div>
        <div class="mini-chart">
            @forelse($chart as $c)
                @php $mx = max(1, (int) $c->stock_in, (int) $c->stock_out); @endphp
                <div class="bar-col">
                    <div class="bars">
                        <i style="height:{{ max(4, (int) $c->stock_in / $mx * 100) }}%" title="IN {{ $c->stock_in }}"></i>
                        <b style="height:{{ max(4, (int) $c->stock_out / $mx * 100) }}%" title="OUT {{ $c->stock_out }}"></b>
                    </div>
                    <small>{{ \Illuminate\Support\Carbon::parse($c->day)->format('d/m') }}</small>
                </div>
            @empty
                <div class="empty">No movement data yet.</div>
            @endforelse
        </div>
        <div class="legend"><span><i class="dot in"></i>Stock In</span><span><i class="dot out"></i>Stock Out</span></div>
    </section>

    <section class="card">
        <div class="card-head">
            <div><h2>Low Stock Products</h2><p>Products requiring attention.</p></div>
            <a href="{{ route('products.index', ['status' => 'low']) }}">View all</a>
        </div>
        <div class="list">
            @forelse($lowProducts as $p)
                <a class="list-row" href="{{ route('products.show', $p) }}">
                    <div><b>{{ $p->name }}</b><small>{{ $p->sku }}</small></div>
                    <div class="stock-number">{{ $p->stock }} / {{ $p->minimum_stock }}</div>
                </a>
            @empty
                <div class="empty">No low stock products.</div>
            @endforelse
        </div>
    </section>
</div>

<section class="card">
    <div class="card-head">
        <div><h2>Recent Transactions</h2><p>Latest inventory movements.</p></div>
        <a href="{{ route('stock.history') }}">View history</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Product</th><th>Type</th><th>Quantity</th><th>User</th><th>Date</th></tr></thead>
            <tbody>
            @forelse($recent as $r)
                <tr>
                    <td>{{ $r->product->name }}</td>
                    <td><span class="badge {{ $r->type === 'IN' ? 'success' : 'danger' }}">{{ $r->type }}</span></td>
                    <td>{{ $r->quantity }}</td>
                    <td>{{ $r->user->username ?? $r->user->name }}</td>
                    <td>{{ $r->movement_date->format('d M Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">No transactions yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
