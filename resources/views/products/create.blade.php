<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Laravel</title>
</head>
<body>
    <h1>Tambah Barang Baru</h1>
    
    <form action="/products" method="POST">
        <!-- @csrf ini wajib ada di Laravel supaya form aman dari hacker -->
        @csrf
        
        <label>SKU:</label><br>
        <input type="text" name="sku" required><br><br>
        
        <label>Nama Barang:</label><br>
        <input type="text" name="name" required><br><br>
        
        <label>Deskripsi:</label><br>
        <textarea name="description" rows="3" required></textarea><br><br>
        
        <label>Stok:</label><br>
        <input type="number" name="stock" required><br><br>
        
        <label>Harga:</label><br>
        <input type="number" name="price" required><br><br>
        
        <button type="submit">Simpan Barang</button>
        <a href="/products">Batal</a>
    </form>
</body>
</html>