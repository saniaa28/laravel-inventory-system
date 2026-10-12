@extends('layouts.app')
@section('title', 'Suppliers')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">MASTER DATA</span>
        <h1>Suppliers</h1>
        <p>Manage suppliers and product sourcing relationships.</p>
    </div>
</div>

<div class="grid-2">
    <section class="card">
        <div class="card-head"><h2>{{ $editing ? 'Edit Supplier' : 'Add Supplier' }}</h2></div>
        <form method="POST" action="{{ $editing ? route('suppliers.update', $editing) : route('suppliers.store') }}" class="form-grid">
            @csrf
            @if($editing) @method('PUT') @endif
            <x-field label="Supplier Name" name="supplier_name" :value="$editing->supplier_name ?? null" required />
            <x-field label="Contact Person" name="contact_person" :value="$editing->contact_person ?? null" />
            <x-field label="Email" name="email" type="email" :value="$editing->email ?? null" />
            <x-field label="Phone" name="phone" :value="$editing->phone ?? null" />
            <x-field label="Address" name="address" type="textarea" :value="$editing->address ?? null" />
            <button type="submit" class="btn primary">{{ $editing ? 'Save Changes' : 'Add Supplier' }}</button>
            @if($editing)
                <a class="btn ghost" href="{{ route('suppliers.index') }}">Cancel</a>
            @endif
        </form>
    </section>

    <section class="card">
        <div class="tabs">
            <a class="btn tiny {{ $view === 'active' ? 'active' : '' }}" href="{{ route('suppliers.index') }}">Active</a>
            <a class="btn tiny {{ $view === 'archived' ? 'active' : '' }}" href="{{ route('suppliers.index', ['view' => 'archived']) }}">Archived</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Supplier</th><th>Contact</th><th>Email</th><th>Products</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse($suppliers as $s)
                    <tr>
                        <td><b>{{ $s->supplier_name }}</b></td>
                        <td>{{ $s->contact_person }}</td>
                        <td>{{ $s->email ?? '-' }}</td>
                        <td>{{ $s->products_count }}</td>
                        <td>
                            <a class="btn tiny" href="{{ route('suppliers.edit', $s) }}">Edit</a>
                            @if($view === 'active')
                                <form method="POST" action="{{ route('suppliers.archive', $s) }}" class="inline"
                                      onsubmit="return confirm('Archive this supplier? You can restore it later.');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn tiny danger-btn">Archive</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('suppliers.restore', $s) }}" class="inline"
                                      onsubmit="return confirm('Restore this supplier?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn tiny">Restore</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">No suppliers found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
