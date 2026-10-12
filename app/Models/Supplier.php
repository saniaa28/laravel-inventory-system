<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    // Supplier dan produk: many-to-many lewat tabel product_supplier
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('supplier_price')->withTimestamps();
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Hanya supplier yang tidak diarsipkan
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
