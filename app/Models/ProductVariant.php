<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $guarded = [];

     public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getAvailableAttribute(): int
    {
        return max(0, $this->stock - $this->reserved);
    }

    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function orders() { return $this->hasMany(Order::class); }
    //
}
