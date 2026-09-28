<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredOrders extends Command
{
    protected $signature = 'orders:release-expired';

    protected $description = 'Batalkan pesanan yang kedaluwarsa dan lepas stok yang dikunci';

    public function handle(): int
    {
        $ids = Order::where('status', 'pending')
            ->where('payment_status', 'unpaid')
            ->where('expires_at', '<', now())
            ->pluck('id');

        foreach ($ids as $id) {
            DB::transaction(function () use ($id) {
                /** @var Order|null $order */
                $order = Order::with('items')->lockForUpdate()->find($id);

                if (! $order || $order->status !== 'pending' || $order->payment_status !== 'unpaid') {
                    return;
                }

                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::whereKey($item->product_variant_id)->update([
                            'reserved' => DB::raw('GREATEST(reserved - ' . (int) $item->quantity . ', 0)'),
                        ]);
                    }
                }

                $order->update(['status' => 'cancelled', 'payment_status' => 'expired']);
            });
        }

        $this->info($ids->count() . ' pesanan kedaluwarsa dibatalkan.');

        return self::SUCCESS;
    }
}