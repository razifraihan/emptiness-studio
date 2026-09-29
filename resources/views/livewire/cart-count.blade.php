<a href="{{ route('cart') }}" wire:navigate class="hover:text-black">
    Cart
    @if ($count)
        ({{ $count }})
    @endif
</a>