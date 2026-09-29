@php
    $project = $product->project;
    $multiColor = $product->variants->pluck('color')->filter()->unique()->count() > 1;
@endphp

<div class="mx-auto max-w-6xl px-6 py-16">
    <a href="{{ route('shop') }}" wire:navigate class="text-sm text-neutral-500 hover:text-black">&larr; Shop</a>

    <div class="mt-8 grid gap-12 md:grid-cols-2">
        <div class="space-y-4">
            @forelse ($product->images as $image)
                <img src="{{ asset('storage/' . $image->path) }}"
                     alt="{{ $image->alt ?: $product->name }}"
                     class="w-full bg-neutral-100 object-cover" loading="lazy">
            @empty
                <div class="aspect-[4/5] w-full bg-neutral-100"></div>
            @endforelse
        </div>

        <div>
            @if ($product->category)
                <p class="text-xs uppercase tracking-widest text-neutral-500">{{ $product->category->name }}</p>
            @endif

            <h1 class="mt-2 text-3xl font-semibold">{{ $product->name }}</h1>

            <p class="mt-3 text-lg">
                Rp {{ number_format(($variant?->price ?? $product->from_price) ?? 0, 0, ',', '.') }}
            </p>

            @if ($project)
                <p class="mt-3 text-sm text-neutral-500">
    <a href="{{ route('project.show', $project->slug) }}" wire:navigate class="hover:text-black">
        @if ($project->number)Project {{ str_pad($project->number, 2, '0', STR_PAD_LEFT) }} &middot; @endif{{ $project->name }}
    </a>
</p>

                @if ($project->is_preorder)
                    <p class="mt-3 text-sm text-neutral-600">
                        Pre-order.
                        @if ($project->preorder_note){{ $project->preorder_note }}@endif
                    </p>
                @endif
            @endif

            @if ($product->description)
                <p class="mt-8 whitespace-pre-line text-neutral-700">{{ $product->description }}</p>
            @endif

            <div class="mt-8">
                <p class="text-sm text-neutral-500">Select variant</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($product->variants as $v)
                        @php
                            $qty = max(0, $v->stock - $v->reserved);
                            $parts = $multiColor ? [$v->size, $v->color] : [$v->size];
                            $label = trim(implode(' ', array_filter($parts))) ?: 'One size';
                        @endphp
                        <button type="button"
                                wire:key="variant-{{ $v->id }}"
                                wire:click="selectVariant({{ $v->id }})"
                                @disabled($qty < 1)
                                class="border px-4 py-2 text-sm
                                    {{ $variantId === $v->id ? 'border-black bg-black text-white' : 'border-neutral-300' }}
                                    {{ $qty < 1 ? 'cursor-not-allowed text-neutral-400 line-through' : '' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                @if ($variant)
                    <p class="mt-3 text-sm text-neutral-500">
                        @if ($showRemaining)
                            {{ $available }} left
                        @else
                            Available
                        @endif
                    </p>
                @endif
            </div>

            <div class="mt-8">
                @if ($product->is_sold_out)
                    <p class="text-sm uppercase tracking-widest text-neutral-500">Sold Out</p>
                @elseif (! $open)
                    <p class="text-sm text-neutral-600">
                        @if ($project->opens_at && $project->opens_at->isFuture())
                            Opens {{ $project->opens_at->locale('en')->translatedFormat('j F Y, H:i') }}
                        @elseif (in_array($project->status, ['closed', 'archived']) || ($project->closes_at && $project->closes_at->isPast()))
                            Sales for this project have closed.
                        @else
                            Sales have not opened yet.
                        @endif
                    </p>
                @else
                    <button type="button" wire:click="addToCart" @disabled(! $variant)
                            class="w-full border border-black bg-black px-6 py-3 text-sm text-white disabled:cursor-not-allowed disabled:border-neutral-300 disabled:bg-neutral-200 disabled:text-neutral-500">
                        {{ $variant ? 'Add to cart' : 'Select a variant first' }}
                    </button>

                    @if ($added)
                        <p class="mt-3 text-sm text-neutral-600">Added to your cart.</p>
                    @endif
                @endif
            </div>

            @if ($product->material)
                <div class="mt-10">
                    <h2 class="text-sm uppercase tracking-widest text-neutral-500">Material &amp; care</h2>
                    <p class="mt-3 whitespace-pre-line text-sm text-neutral-700">{{ $product->material }}</p>
                </div>
            @endif

            @if (! empty($product->size_chart))
                <div class="mt-10">
                    <h2 class="text-sm uppercase tracking-widest text-neutral-500">Size chart</h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-neutral-200 text-neutral-500">
                                    <th class="py-2 pr-4 font-normal">Size</th>
                                    <th class="py-2 pr-4 font-normal">Chest width (cm)</th>
                                    <th class="py-2 font-normal">Length (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->size_chart as $row)
                                    <tr class="border-b border-neutral-100">
                                        <td class="py-2 pr-4">{{ $row['size'] ?? '' }}</td>
                                        <td class="py-2 pr-4">{{ $row['chest_cm'] ?? '-' }}</td>
                                        <td class="py-2">{{ $row['length_cm'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($project && ($project->concept || $project->key_message))
                <div class="mt-10 border-t border-neutral-200 pt-8">
                    <h2 class="text-sm uppercase tracking-widest text-neutral-500">About this project</h2>
                    @if ($project->concept)
                        <p class="mt-3 text-sm text-neutral-700">{{ $project->concept }}</p>
                    @endif
                    @if ($project->key_message)
                        <p class="mt-3 text-sm italic text-neutral-600">{{ $project->key_message }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>