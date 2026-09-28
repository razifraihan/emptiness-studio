<a href="{{ route('cart') }}" wire:navigate class="hover:text-black">
    Keranjang
    @if ($count)
        ({{ $count }})
    @endif
</a>