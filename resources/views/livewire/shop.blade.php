<div class="mx-auto max-w-6xl px-6 py-16">
    <h1 class="text-3xl font-semibold">Shop</h1>

    <div class="mt-8">
        <input type="search" wire:model.live.debounce.400ms="q" placeholder="Search products"
               class="w-full border border-neutral-300 px-4 py-3 text-sm outline-none focus:border-black">
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 text-sm">
        <div class="flex flex-wrap gap-2">
            <button wire:click="$set('category', '')"
                    class="border px-4 py-2 {{ $category === '' ? 'border-black bg-black text-white' : 'border-neutral-300' }}">
                All
            </button>
            @foreach ($categories as $cat)
                <button wire:click="$set('category', '{{ $cat->slug }}')"
                        class="border px-4 py-2 {{ $category === $cat->slug ? 'border-black bg-black text-white' : 'border-neutral-300' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <select wire:model.live="sort" class="border border-neutral-300 px-3 py-2">
            <option value="newest">Newest</option>
            <option value="price_asc">Price: low to high</option>
            <option value="price_desc">Price: high to low</option>
        </select>
    </div>

    <details class="mt-6 border border-neutral-200" @if ($activeFilters) open @endif>
        <summary class="cursor-pointer px-4 py-3 text-sm">
            Filters @if ($activeFilters) ({{ $activeFilters }}) @endif
        </summary>

        <div class="grid gap-8 border-t border-neutral-200 px-4 py-6 text-sm md:grid-cols-3">
            @if (count($sizeOptions))
                <div>
                    <p class="text-xs uppercase tracking-widest text-neutral-500">Size</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($sizeOptions as $opt)
                            <label class="cursor-pointer" wire:key="size-{{ $opt }}">
                                <input type="checkbox" wire:model.live="sizes" value="{{ $opt }}" class="peer sr-only">
                                <span class="block border border-neutral-300 px-3 py-1.5 peer-checked:border-black peer-checked:bg-black peer-checked:text-white">{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (count($colorOptions))
                <div>
                    <p class="text-xs uppercase tracking-widest text-neutral-500">Color</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($colorOptions as $opt)
                            <label class="cursor-pointer" wire:key="color-{{ $opt }}">
                                <input type="checkbox" wire:model.live="colors" value="{{ $opt }}" class="peer sr-only">
                                <span class="block border border-neutral-300 px-3 py-1.5 peer-checked:border-black peer-checked:bg-black peer-checked:text-white">{{ $opt }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <p class="text-xs uppercase tracking-widest text-neutral-500">Price (Rp)</p>
                <div class="mt-3 flex items-center gap-2">
                    <input type="text" inputmode="numeric" wire:model.live.debounce.600ms="min" placeholder="Min"
                           class="w-full border border-neutral-300 px-3 py-2 outline-none focus:border-black">
                    <span class="text-neutral-400">&ndash;</span>
                    <input type="text" inputmode="numeric" wire:model.live.debounce.600ms="max" placeholder="Max"
                           class="w-full border border-neutral-300 px-3 py-2 outline-none focus:border-black">
                </div>
            </div>
        </div>
    </details>

    <div class="mt-6 flex items-center justify-between text-sm text-neutral-500">
        <p>{{ $products->total() }} {{ \Illuminate\Support\Str::plural('piece', $products->total()) }}</p>
        @if ($hasAnyFilter)
            <button wire:click="clearFilters" class="underline hover:text-black">Clear all</button>
        @endif
    </div>

    <div class="mt-8 grid grid-cols-2 gap-x-6 gap-y-12 md:grid-cols-3 lg:grid-cols-4">
        @forelse ($products as $product)
            <x-product-card :product="$product" wire:key="product-{{ $product->id }}" />
        @empty
            <div class="col-span-full text-neutral-500">
                <p>No products match your selection.</p>
                @if ($hasAnyFilter)
                    <button wire:click="clearFilters" class="mt-3 underline hover:text-black">Clear all filters</button>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $products->links() }}
    </div>
</div>