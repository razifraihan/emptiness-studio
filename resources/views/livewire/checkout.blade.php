@php
    $inputClass = 'mt-1 w-full border border-neutral-300 px-3 py-2 text-sm focus:border-black focus:outline-none';
    $contact = [
        'customer_name' => ['Full name', 'text'],
        'customer_email' => ['Email', 'email'],
        'customer_phone' => ['WhatsApp number', 'tel'],
    ];
    $region = [
        'province' => ['Province', 'text'],
        'city' => ['City / regency', 'text'],
        'district' => ['District', 'text'],
        'postal_code' => ['Postal code', 'text'],
    ];
@endphp

<div class="mx-auto max-w-5xl px-6 py-16">
    <h1 class="text-3xl font-semibold">Checkout</h1>

    <div class="mt-10 grid gap-12 md:grid-cols-5">
        <form wire:submit="placeOrder" class="space-y-6 md:col-span-3">
            @if ($error)
                <p class="border border-neutral-300 bg-neutral-50 px-4 py-3 text-sm">{{ $error }}</p>
            @endif

            <div class="space-y-4">
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Contact</h2>
                @foreach ($contact as $field => [$label, $type])
                    <label class="block text-sm">
                        {{ $label }}
                        <input type="{{ $type }}" wire:model="{{ $field }}" class="{{ $inputClass }}">
                        @error($field) <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endforeach
            </div>

            <div class="space-y-4">
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Shipping address</h2>

                <label class="block text-sm">
                    Full address
                    <textarea wire:model="address" rows="3" class="{{ $inputClass }}"></textarea>
                    @error('address') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                </label>

                @foreach ($region as $field => [$label, $type])
                    <label class="block text-sm">
                        {{ $label }}
                        <input type="{{ $type }}" wire:model="{{ $field }}" class="{{ $inputClass }}">
                        @error($field) <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endforeach
            </div>

            <div class="space-y-4">
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Notes (optional)</h2>

                <label class="block text-sm">
                    Order note
                    <textarea wire:model="note" rows="2" class="{{ $inputClass }}"></textarea>
                </label>

                <label class="block text-sm">
                    Gift message
                    <textarea wire:model="gift_note" rows="2" class="{{ $inputClass }}"></textarea>
                </label>
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full border border-black bg-black px-6 py-3 text-sm text-white disabled:opacity-60">
                Place order
            </button>
        </form>

        <aside class="md:col-span-2">
            <h2 class="text-sm uppercase tracking-widest text-neutral-500">Summary</h2>

            <div class="mt-4 divide-y divide-neutral-200 border-y border-neutral-200 text-sm">
                @foreach ($lines as $line)
                    <div class="flex justify-between gap-4 py-4">
                        <div>
                            <p>{{ $line['name'] }} &times; {{ $line['qty'] }}</p>
                            @if ($line['label'])
                                <p class="mt-1 text-neutral-500">{{ $line['label'] }}</p>
                            @endif
                        </div>
                        <p>Rp {{ number_format($line['total'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <dl class="mt-6 space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-neutral-500">Subtotal</dt>
                    <dd>Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-neutral-500">Shipping</dt>
                    <dd class="text-right text-neutral-500">Confirmed after your order is placed</dd>
                </div>
                <div class="flex justify-between pt-2 text-base">
                    <dt>Total</dt>
                    <dd>Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                </div>
            </dl>
        </aside>
    </div>
</div>