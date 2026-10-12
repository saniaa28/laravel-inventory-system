@extends('layouts.app')
@section('title', 'Inventory Reports')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">REPORTS</span>
        <h1>Inventory Reports</h1>
        <p>Aggregate data for inventory review and presentation.</p>
    </div>
</div>

<div class="kpis three">
    <div class="kpi"><span>Products</span><strong>{{ $rows->count() }}</strong><small>Product summary rows</small></div>
    <div class="kpi"><span>Total In</span><strong>{{ number_format($totalIn) }}</strong><small>SUM of incoming quantity</small></div>
    <div class="kpi"><span>Total Out</span><strong>{{ number_format($totalOut) }}</strong><small>SUM of outgoing quantity</small></div>
</div>

<section class="card">
    <div class="card-head">
        <div><h2>Product Summary</h2><p>GROUP BY product with SUM and AVG aggregate functions.</p></div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>SKU</th><th>Product</th><th>Total In</th><th>Total Out</th><th>Current Stock</th><th>Avg In</th></tr></thead>
            <tbody>
            @forelse($rows as $r)
                <tr>
                    <td><code>{{ $r->sku }}</code></td>
                    <td>{{ $r->name }}</td>
                    <td>{{ (int) $r->total_in }}</td>
                    <td>{{ (int) $r->total_out }}</td>
                    <td><b>{{ $r->stock }}</b></td>
                    <td>{{ $r->avg_in !== null ? number_format($r->avg_in, 1) : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">No data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
