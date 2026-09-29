<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'Menunggu pembayaran',
        'paid' => 'Lunas',
        'packed' => 'Dikemas',
        'shipped' => 'Dikirim',
        'done' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public const PAYMENT_STATUSES = [
        'unpaid' => 'Belum bayar',
        'paid' => 'Lunas',
        'expired' => 'Kedaluwarsa',
        'refunded' => 'Dikembalikan',
    ];

    protected $guarded = [];

    protected static function booted(): void
    {
        static::updated(function (Order $order) {
            if ($order->wasChanged('status') && $order->status === 'shipped') {
                try {
                    \Illuminate\Support\Facades\Mail::to($order->customer_email)
                        ->send(new \App\Mail\OrderShipped($order));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Pembayaran diterima: stok dipotong dan stok terkunci dilepas.
     * Hanya berlaku untuk pesanan yang masih menunggu pembayaran.
     */
    public function markPaid(): bool
    {
        $done = DB::transaction(function () {
            $order = static::with('items')->lockForUpdate()->find($this->id);

            if (! $order || $order->status !== 'pending' || $order->payment_status !== 'unpaid') {
                return false;
            }

            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $qty = (int) $item->quantity;

                    ProductVariant::whereKey($item->product_variant_id)->update([
                        'stock' => DB::raw("GREATEST(stock - {$qty}, 0)"),
                        'reserved' => DB::raw("GREATEST(reserved - {$qty}, 0)"),
                    ]);
                }
            }

            $order->update([
                'status' => 'paid',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            return true;
        });

        $this->refresh();

        return $done;
    }

    /**
     * Batalkan pesanan yang belum dibayar dan lepas stok terkunci.
     */
    public function cancelUnpaid(): bool
    {
        $done = DB::transaction(function () {
            $order = static::with('items')->lockForUpdate()->find($this->id);

            if (! $order || $order->status !== 'pending' || $order->payment_status !== 'unpaid') {
                return false;
            }

            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $qty = (int) $item->quantity;

                    ProductVariant::whereKey($item->product_variant_id)->update([
                        'reserved' => DB::raw("GREATEST(reserved - {$qty}, 0)"),
                    ]);
                }
            }

            $order->update(['status' => 'cancelled', 'payment_status' => 'expired']);

            return true;
        });

        $this->refresh();

        return $done;
    }
}