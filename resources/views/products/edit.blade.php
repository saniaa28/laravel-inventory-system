<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Laravel</title>
</head>
<body>
    <h1>Edit Data Barang</h1>
    
    <!-- Action mengarah ke rute update berdasarkan ID barang -->
    <form action="/products/{{ $product->id }}" method="POST">
        @csrf
        @method('PUT') <!-- Ini wajib untuk fungsi Edit di Laravel -->
        
        <label>SKU:</label><br>
        <input type="text" name="sku" value="{{ $product->sku }}" required><br><br>
        
        <label>Nama Barang:</label><br>
        <input type="text" name="name" value="{{ $product->name }}" required><br><br>
        
        <label>Deskripsi:</label><br>
        <textarea name="description" rows="3" required>{{ $product->description }}</textarea><br><br>
        
        <label>Stok:</label><br>
        <input type="number" name="stock" value="{{ $product->stock }}" required><br><br>
        
        <label>Harga:</label><br>
        <input type="number" name="price" value="{{ $product->price }}" required><br><br>
        
        <button type="submit">Update Barang</button>
        <a href="/products">Batal</a>
    </form>
</body>
</html>