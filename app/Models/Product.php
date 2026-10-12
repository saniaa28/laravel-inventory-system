<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi lewat create() dan update() (mass assignment)
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'minimum_stock',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    // Satu produk milik satu kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class)->withPivot('supplier_price')->withTimestamps();
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // ---- Query scope: dipakai untuk search dan filter di listing ----

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $q, string $term) {
            $q->where(function (Builder $w) use ($term) {
                $w->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%");
            });
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return match ($status) {
            'out'   => $query->where('stock', 0),
            'low'   => $query->where('stock', '>', 0)->whereColumn('stock', '<=', 'minimum_stock'),
            'in'    => $query->whereColumn('stock', '>', 'minimum_stock'),
            default => $query,
        };
    }

    // Status stok: in, low, atau out
    public function getStatusAttribute(): string
    {
        if ($this->stock === 0) {
            return 'out';
        }

        return $this->stock <= $this->minimum_stock ? 'low' : 'in';
    }
}
