@php
    $statusLabels = [
        'pending' => 'Menunggu pembayaran',
        'paid' => 'Pembayaran diterima',
        'packed' => 'Sedang dikemas',
        'shipped' => 'Dalam pengiriman',
        'done' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];
@endphp

<div class="mx-auto max-w-3xl px-6 py-16">
    <p class="text-xs uppercase tracking-widest text-neutral-500">Terima kasih</p>
    <h1 class="mt-2 text-3xl font-semibold">Pesananmu sudah kami terima</h1>
    <p class="mt-4 text-neutral-600">
        Nomor pesanan <span class="font-medium text-black">{{ $order->code }}</span>.
        Kami akan mengirim instruksi pembayaran dan konfirmasi ongkos kirim lewat email dan WhatsApp.
    </p>

    <p class="mt-6 text-sm">
        Status: <span class="font-medium">{{ $statusLabels[$order->status] ?? $order->status }}</span>
    </p>

    <div class="mt-8 divide-y divide-neutral-200 border-y border-neutral-200 text-sm">
        @foreach ($order->items as $item)
            <div class="flex justify-between gap-4 py-4">
                <div>
                    <p>{{ $item->product_name }} &times; {{ $item->quantity }}</p>
                    @if ($item->variant_label)
                        <p class="mt-1 text-neutral-500">{{ $item->variant_label }}</p>
                    @endif
                </div>
                <p>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>

    <dl class="mt-6 space-y-2 text-sm">
        <div class="flex justify-between">
            <dt class="text-neutral-500">Subtotal</dt>
            <dd>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>
        </div>
        <div class="flex justify-between">
            <dt class="text-neutral-500">Ongkos kirim</dt>
            <dd>
                @if ($order->shipping_cost > 0)
                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                @else
                    <span class="text-neutral-500">Menyusul</span>
                @endif
            </dd>
        </div>
        <div class="flex justify-between pt-2 text-base">
            <dt>Total</dt>
            <dd>Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
        </div>
    </dl>

    <div class="mt-10 text-sm text-neutral-700">
        <h2 class="text-xs uppercase tracking-widest text-neutral-500">Dikirim ke</h2>
        <p class="mt-3">{{ $order->customer_name }}</p>
        <p>{{ $order->address }}</p>
        <p>{{ $order->district }}, {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}</p>
    </div>

    <a href="{{ route('shop') }}" wire:navigate
       class="mt-10 inline-block border border-black px-6 py-3 text-sm hover:bg-black hover:text-white">
        Kembali ke Shop
    </a>
</div>