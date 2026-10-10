<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris - Laravel</title>
</head>
<body>
    <h1>Daftar Barang Inventaris</h1>
    
    <a href="/products/create" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">+ Tambah Barang</a>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <a href="/products/{{ $product->id }}/edit" style="color: green; text-decoration: none; margin-right: 10px;">Edit</a>
                    
                    <!-- Tombol Hapus (Harus pakai form) -->
                    <form action="/products/{{ $product->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color: red; border: none; background: none; cursor: pointer; text-decoration: underline;" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Belum ada data barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>