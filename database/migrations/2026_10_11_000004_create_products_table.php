<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // restrictOnDelete: kategori yang masih dipakai produk tidak bisa dihapus
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('sku', 80)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            // unsigned: stok tidak bisa negatif di level database
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('minimum_stock')->default(5);
            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
