<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'size_chart' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            OrderItem::class,
            ProductVariant::class,
            'product_id',
            'product_variant_id'
        );
    }

    public function getFromPriceAttribute(): ?int
    {
        return $this->variants->min('price');
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->remaining_stock <= 0;
    }

    public function getRemainingStockAttribute(): int
    {
        return (int) $this->variants->sum(fn ($v) => max(0, $v->stock - $v->reserved));
    }

    public function getShowRemainingAttribute(): bool
    {
        if (! config('shop.show_remaining') || $this->is_sold_out) {
            return false;
        }

        return $this->variants->sum('reserved') > 0 || ($this->sold_count ?? 0) > 0;
    }

    public function getCoverUrlAttribute(): ?string
    {
        $image = $this->images->first();

        return $image ? asset('storage/' . $image->path) : null;
    }
}