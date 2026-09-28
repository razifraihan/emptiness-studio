<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

protected function casts(): array
{
    return [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}

public function user() { return $this->belongsTo(User::class); }
public function items() { return $this->hasMany(OrderItem::class); }
    //
}
