<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <form wire:submit="submit" role="search">
        <input type="search" wire:model.live.debounce.300ms="q" @focus="open = true" @input="open = true"
               placeholder="Search" autocomplete="off" aria-label="Search products"
               class="w-32 border-b border-neutral-300 bg-transparent py-1 text-sm outline-none focus:border-black md:w-44">
    </form>

    @if (mb_strlen($term) >= 2)
        <div x-show="open" x-cloak
             class="absolute right-0 z-20 mt-3 w-64 border border-neutral-200 bg-white text-sm shadow-sm">
            @forelse ($results as $r)
                <a href="{{ route('product', $r->slug) }}" wire:navigate wire:key="suggest-{{ $r->id }}"
                   @click="open = false"
                   class="block px-4 py-3 hover:bg-neutral-50">{{ $r->name }}</a>
            @empty
                <p class="px-4 py-3 text-neutral-500">No matches.</p>
            @endforelse

            <a href="{{ route('shop', ['q' => trim($q)]) }}" wire:navigate @click="open = false"
               class="block border-t border-neutral-200 px-4 py-3 text-neutral-500 hover:text-black">
                See all results
            </a>
        </div>
    @endif
</div>