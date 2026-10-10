<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Memberi tahu Laravel kolom mana saja yang boleh diisi
    protected $fillable = [
        'sku', 
        'name', 
        'description', 
        'stock', 
        'price'
    ];
}