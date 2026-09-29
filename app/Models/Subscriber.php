<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    protected $fillable = ['email', 'token', 'source', 'subscribed_at', 'unsubscribed_at'];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Subscriber $subscriber) {
            $subscriber->token ??= Str::random(48);
            $subscriber->subscribed_at ??= now();
        });
    }
}