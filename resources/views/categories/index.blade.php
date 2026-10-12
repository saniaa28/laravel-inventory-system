@extends('layouts.app')
@section('title', 'Categories')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">MASTER DATA</span>
        <h1>Categories</h1>
        <p>Organize products with clean category relationships.</p>
    </div>
</div>

<div class="grid-2">
    <section class="card">
        <div class="card-head"><h2>{{ $editing ? 'Edit Category' : 'Add Category' }}</h2></div>
        <form method="POST" action="{{ $editing ? route('categories.update', $editing) : route('categories.store') }}" class="form-grid">
            @csrf
            @if($editing) @method('PUT') @endif
            <x-field label="Category Name" name="name" :value="$editing->name ?? null" maxlength="100" required />
            <button type="submit" class="btn primary">{{ $editing ? 'Save Changes' : 'Add Category' }}</button>
            @if($editing)
                <a class="btn ghost" href="{{ route('categories.index') }}">Cancel</a>
            @endif
        </form>
    </section>

    <section class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Name</th><th>Products</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse($categories as $c)
                    <tr>
                        <td><b>{{ $c->name }}</b></td>
                        <td>{{ $c->products_count }}</td>
                        <td>
                            <a class="btn tiny" href="{{ route('categories.edit', $c) }}">Edit</a>
                            <form method="POST" action="{{ route('categories.destroy', $c) }}" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn tiny danger-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty">No categories found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
