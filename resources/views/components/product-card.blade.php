@props(['product'])

@php
    $label = $product->is_sold_out
        ? 'Sold Out'
        : ($product->project?->is_preorder
            ? 'Pre-order'
            : ($product->created_at->gt(now()->subDays(30)) ? 'New' : null));
@endphp

<a href="{{ route('product', $product->slug) }}" wire:navigate class="group block">
    <div class="relative overflow-hidden bg-neutral-100">
        @if ($product->cover_url)
            <img src="{{ $product->cover_url }}" alt="{{ $product->name }}" loading="lazy"
                 class="aspect-[4/5] w-full object-cover">
        @else
            <div class="aspect-[4/5] w-full"></div>
        @endif

        @if ($label)
            <span class="absolute left-3 top-3 bg-white px-2 py-1 text-[11px] uppercase tracking-widest">{{ $label }}</span>
        @endif
    </div>
    <div class="mt-4 flex items-baseline justify-between gap-4 text-sm">
        <span>{{ $product->name }}</span>
        <span class="text-neutral-500">Rp {{ number_format($product->from_price ?? 0, 0, ',', '.') }}</span>
    </div>

    @if ($product->show_remaining)
        <p class="mt-1 text-xs text-neutral-500">Sisa {{ $product->remaining_stock }}</p>
    @endif
</a>