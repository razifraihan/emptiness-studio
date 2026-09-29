<div>
    <section class="mx-auto max-w-6xl px-6 py-16 md:py-24">
        <a href="{{ route('project.index') }}" wire:navigate class="text-sm text-neutral-500 hover:text-black">&larr; Project</a>

        @if ($project->cover_image)
            <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->name }}"
                 class="mt-8 max-h-[28rem] w-full bg-neutral-100 object-cover">
        @endif

        @if ($project->number)
            <p class="mt-10 text-xs uppercase tracking-widest text-neutral-500">
                Project {{ str_pad($project->number, 2, '0', STR_PAD_LEFT) }}
            </p>
        @endif

        <h1 class="mt-3 text-4xl font-semibold md:text-6xl">{{ $project->name }}</h1>

        @if ($project->key_message)
            <p class="mt-6 max-w-2xl text-lg italic text-neutral-600">{{ $project->key_message }}</p>
        @endif

        <p class="mt-6 text-sm text-neutral-600">
            @if ($project->opens_at && $project->opens_at->isFuture())
                Opens {{ $project->opens_at->locale('en')->translatedFormat('j F Y, H:i') }}
            @elseif ($project->is_open_for_sale)
                Sales are now open.
            @else
                Sales for this project have closed.
            @endif

            @if ($project->is_preorder)
                Pre-order.
                @if ($project->preorder_note){{ $project->preorder_note }}@endif
            @endif
        </p>

        @if ($project->concept)
            <div class="mt-12 max-w-2xl">
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Concept</h2>
                <p class="mt-3 whitespace-pre-line text-neutral-700">{{ $project->concept }}</p>
            </div>
        @endif

        @if ($project->narrative)
            <div class="mt-12 max-w-2xl">
                <h2 class="text-sm uppercase tracking-widest text-neutral-500">Story</h2>
                <p class="mt-3 whitespace-pre-line leading-relaxed text-neutral-700">{{ $project->narrative }}</p>
            </div>
        @endif
    </section>

    <section class="mx-auto max-w-6xl px-6">
        <h2 class="mb-8 text-sm uppercase tracking-widest text-neutral-500">Collection</h2>

        <div class="grid grid-cols-2 gap-x-6 gap-y-12 md:grid-cols-4">
            @forelse ($products as $product)
                <x-product-card :product="$product" wire:key="product-{{ $product->id }}" />
            @empty
                <p class="col-span-full text-neutral-500">Products are coming soon.</p>
            @endforelse
        </div>
    </section>
</div>