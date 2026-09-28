<div class="mx-auto max-w-6xl px-6 py-16">
    <h1 class="text-3xl font-semibold">Shop</h1>

    <div class="mt-8 flex flex-wrap items-center justify-between gap-4 text-sm">
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('category', '')"
                    class="border px-4 py-2 {{ $category === '' ? 'border-black bg-black text-white' : 'border-neutral-300' }}">
                Semua
            </button>
            @foreach ($categories as $cat)
                <button wire:click="$set('category', '{{ $cat->slug }}')"
                        class="border px-4 py-2 {{ $category === $cat->slug ? 'border-black bg-black text-white' : 'border-neutral-300' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <select wire:model.live="sort" class="border border-neutral-300 px-3 py-2">
            <option value="newest">Terbaru</option>
            <option value="price_asc">Harga terendah</option>
            <option value="price_desc">Harga tertinggi</option>
        </select>
    </div>

    <div class="mt-12 grid grid-cols-2 gap-x-6 gap-y-12 md:grid-cols-3 lg:grid-cols-4">
        @forelse ($products as $product)
            <x-product-card :product="$product" wire:key="product-{{ $product->id }}" />
        @empty
            <p class="col-span-full text-neutral-500">Belum ada produk di kategori ini.</p>
        @endforelse
    </div>
</div>