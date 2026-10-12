@extends('layouts.app')
@section('title', $type === 'IN' ? 'Stock In' : 'Stock Out')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">INVENTORY</span>
        <h1>{{ $type === 'IN' ? 'Stock In' : 'Stock Out' }}</h1>
        <p>
            {{ $type === 'IN'
                ? 'Record incoming inventory with an atomic database transaction.'
                : 'Reduce stock safely with row locking and rollback protection.' }}
        </p>
    </div>
</div>

<section class="card">
    <form method="POST" action="{{ $type === 'IN' ? route('stock.in.store') : route('stock.out.store') }}" class="form-grid two">
        @csrf

        <x-field label="Product" name="product_id" type="select" required>
            <option value="">Choose product</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" @selected(old('product_id', request('product_id')) == $p->id)>
                    {{ $p->sku }} — {{ $p->name }} ({{ $type === 'IN' ? 'stock' : 'available' }} {{ $p->stock }})
                </option>
            @endforeach
        </x-field>

        @if($type === 'IN')
            <x-field label="Supplier" name="supplier_id" type="select">
                <option value="">No supplier</option>
                @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->supplier_name }}</option>
                @endforeach
            </x-field>
        @else
            <div></div>
        @endif

        <x-field label="Quantity" name="quantity" type="number" min="1" required />
        <x-field label="Date" name="date" type="date" :value="now()->toDateString()" required />
        <x-field label="Notes" name="notes" type="textarea" placeholder="Optional notes" class="full-field" />

        <div class="form-actions">
            <a class="btn ghost" href="{{ route('stock.history') }}">Cancel</a>
            <button type="submit" class="btn primary">{{ $type === 'IN' ? 'Complete Stock In' : 'Complete Stock Out' }}</button>
        </div>
    </form>
</section>
@endsection
