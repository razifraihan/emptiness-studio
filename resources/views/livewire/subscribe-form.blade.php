<div class="relative">
    <h2 class="text-sm uppercase tracking-widest text-neutral-500">Quiet updates</h2>
    <p class="mt-3 text-neutral-600">A short note when a new project opens. Nothing more.</p>

    @if ($done)
        <p class="mt-6 text-neutral-700">Thank you. We'll write when the next project opens.</p>
    @else
        <form wire:submit="submit" class="mt-6">
            <div class="flex gap-3">
                <input type="email" wire:model="email" placeholder="Email address" autocomplete="email"
                       class="w-full border border-neutral-300 px-4 py-3 text-sm outline-none focus:border-black">
                <button type="submit"
                        class="border border-black px-6 py-3 text-sm hover:bg-black hover:text-white">
                    Subscribe
                </button>
            </div>

            <div class="absolute left-[-9999px]" aria-hidden="true">
                <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            @error('email')
                <p class="mt-2 text-sm text-neutral-500">{{ $message }}</p>
            @enderror

            <p class="mt-3 text-xs text-neutral-500">
                By subscribing, you agree to our
                <a href="{{ url('/info/privacy-policy') }}" class="underline hover:text-black">Privacy Policy</a>.
            </p>
        </form>
    @endif
</div>