@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">PRODUCTS</span>
        <h1>Add Product</h1>
        <p>Create a new inventory product.</p>
    </div>
</div>

<section class="card">
    <form method="POST" action="{{ route('products.store') }}" class="form-grid two">
        @csrf
        @include('products._form')
        <div class="form-actions">
            <a class="btn ghost" href="{{ route('products.index') }}">Cancel</a>
            <button type="submit" class="btn primary">Save Product</button>
        </div>
    </form>
</section>
@endsection
