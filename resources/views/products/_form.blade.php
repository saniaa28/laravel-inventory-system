{{-- Form dipakai bersama oleh halaman create dan edit --}}
@php $isEdit = isset($product); @endphp

<x-field label="Product Name" name="name" :value="$product->name ?? null" required />
<x-field label="SKU" name="sku" :value="$product->sku ?? null" required />

<x-field label="Category" name="category_id" type="select" required>
    <option value="">Choose category</option>
    @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
    @endforeach
</x-field>

<x-field label="Price" name="price" type="number" :value="$product->price ?? 0" min="0" step="0.01" required />

@if($isEdit)
    <label>
        Current Stock
        <input type="number" value="{{ $product->stock }}" disabled>
        <small>Stock changes belong in Stock In/Out.</small>
    </label>
@else
    <x-field label="Initial Stock" name="stock" type="number" :value="1" min="0" required />
@endif

<x-field label="Minimum Stock" name="minimum_stock" type="number" :value="$product->minimum_stock ?? 5" min="0" required />
<x-field label="Description" name="description" type="textarea" :value="$product->description ?? null" class="full-field" />
