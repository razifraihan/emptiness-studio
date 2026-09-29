<div class="mx-auto max-w-xl px-6 py-24">
    @if ($done)
        <h1 class="text-3xl font-semibold">You're unsubscribed.</h1>
        <p class="mt-6 text-neutral-600">No more emails from us. You are always welcome back.</p>
    @else
        <h1 class="text-3xl font-semibold">Unsubscribe</h1>
        <p class="mt-6 text-neutral-600">Would you like to stop receiving updates from Emptiness Studio?</p>

        <button wire:click="confirm"
                class="mt-8 border border-black px-6 py-3 text-sm hover:bg-black hover:text-white">
            Yes, unsubscribe
        </button>
    @endif
</div>