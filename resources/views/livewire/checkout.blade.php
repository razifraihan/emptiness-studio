@php
    $inputClass = 'mt-1 w-full border border-neutral-300 px-3 py-2 text-sm focus:border-black focus:outline-none';
    $contact = [
        'customer_name' => ['Nama lengkap', 'text'],
        'customer_email' => ['Email', 'email'],
        'customer_phone' => ['Nomor WhatsApp', 'tel'],
    ];
    $region = [
        'province' => ['Provinsi', 'text'],
        'city' => ['Kota / kabupaten', 'text'],
        'district' => ['Kecamatan', 'text'],
        'postal_code' => ['Kode pos', 'text'],
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
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Kontak</h2>
                @foreach ($contact as $field => [$label, $type])
                    <label class="block text-sm">
                        {{ $label }}
                        <input type="{{ $type }}" wire:model="{{ $field }}" class="{{ $inputClass }}">
                        @error($field) <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endforeach
            </div>

            <div class="space-y-4">
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Alamat pengiriman</h2>

                <label class="block text-sm">
                    Alamat lengkap
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
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Catatan (opsional)</h2>

                <label class="block text-sm">
                    Catatan pesanan
                    <textarea wire:model="note" rows="2" class="{{ $inputClass }}"></textarea>
                </label>

                <label class="block text-sm">
                    Pesan hadiah
                    <textarea wire:model="gift_note" rows="2" class="{{ $inputClass }}"></textarea>
                </label>
            </div>

            <button type="submit" wire:loading.attr="disabled"
                    class="w-full border border-black bg-black px-6 py-3 text-sm text-white disabled:opacity-60">
                Buat pesanan
            </button>
        </form>

        <aside class="md:col-span-2">
            <h2 class="text-sm uppercase tracking-widest text-neutral-500">Ringkasan</h2>

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
                <div class="flex justify-between">
                    <dt class="text-neutral-500">Ongkos kirim</dt>
                    <dd class="text-neutral-500">Dikonfirmasi setelah pesanan dibuat</dd>
                </div>
                <div class="flex justify-between pt-2 text-base">
                    <dt>Total</dt>
                    <dd>Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
                </div>
            </dl>
        </aside>
    </div>
</div>