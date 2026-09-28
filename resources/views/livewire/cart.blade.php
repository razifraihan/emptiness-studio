<div class="mx-auto max-w-4xl px-6 py-16">
    <h1 class="text-3xl font-semibold">Keranjang</h1>

    @if (count($lines))
        <div class="mt-10 divide-y divide-neutral-200 border-y border-neutral-200">
            @foreach ($lines as $line)
                <div wire:key="line-{{ $line['id'] }}" class="flex gap-6 py-6">
                    <a href="{{ route('product', $line['slug']) }}" wire:navigate class="w-24 shrink-0 bg-neutral-100">
                        @if ($line['image'])
                            <img src="{{ $line['image'] }}" alt="{{ $line['name'] }}" class="aspect-[4/5] w-full object-cover">
                        @else
                            <div class="aspect-[4/5] w-full"></div>
                        @endif
                    </a>

                    <div class="flex flex-1 flex-col justify-between text-sm">
                        <div class="flex justify-between gap-4">
                            <div>
                                <p>{{ $line['name'] }}</p>
                                @if ($line['label'])
                                    <p class="mt-1 text-neutral-500">{{ $line['label'] }}</p>
                                @endif
                                @if ($line['unavailable'])
                                    <p class="mt-1 text-neutral-500">Sold Out</p>
                                @endif
                            </div>
                            <p>Rp {{ number_format($line['total'], 0, ',', '.') }}</p>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center border border-neutral-300">
                                <button type="button" wire:click="decrement({{ $line['id'] }})"
                                        class="px-3 py-1 hover:bg-neutral-100" aria-label="Kurangi">&minus;</button>
                                <span class="min-w-8 px-2 text-center">{{ $line['qty'] }}</span>
                                <button type="button" wire:click="increment({{ $line['id'] }})"
                                        @disabled($line['unavailable'] || $line['qty'] >= $line['available'])
                                        class="px-3 py-1 hover:bg-neutral-100 disabled:cursor-not-allowed disabled:text-neutral-300"
                                        aria-label="Tambah">+</button>
                            </div>

                            <button type="button" wire:click="remove({{ $line['id'] }})"
                                    class="text-neutral-500 hover:text-black">Hapus</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex items-baseline justify-between">
            <p class="text-neutral-500">Subtotal</p>
            <p class="text-xl">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
        </div>
        <p class="mt-2 text-sm text-neutral-500">Ongkos kirim dihitung di halaman checkout.</p>

                @if (collect($lines)->contains('unavailable', true))
            <p class="mt-8 text-sm text-neutral-500">
                Ada produk yang sudah sold out. Hapus dari keranjang untuk melanjutkan.
            </p>
        @else
            <a href="{{ route('checkout') }}" wire:navigate
               class="mt-8 block w-full border border-black bg-black px-6 py-3 text-center text-sm text-white">
                Lanjut ke checkout
            </a>
        @endif
    @else
        <p class="mt-10 text-neutral-500">Keranjangmu masih kosong.</p>
        <a href="{{ route('shop') }}" wire:navigate
           class="mt-6 inline-block border border-black px-6 py-3 text-sm hover:bg-black hover:text-white">
            Lihat koleksi
        </a>
    @endif
</div>