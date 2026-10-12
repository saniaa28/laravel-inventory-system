@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">PRODUCTS</span>
        <h1>Edit Product</h1>
        <p>Update product master data. Stock changes belong in Stock In/Out.</p>
    </div>
</div>

<section class="card">
    <form method="POST" action="{{ route('products.update', $product) }}" class="form-grid two">
        @csrf
        @method('PUT')
        @include('products._form')
        <div class="form-actions">
            <a class="btn ghost" href="{{ route('products.index') }}">Cancel</a>
            <button type="submit" class="btn primary">Save Changes</button>
        </div>
    </form>
</section>
@endsection
