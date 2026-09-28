<div>
    @if ($project)
        <section class="mx-auto max-w-6xl px-6 py-24 md:py-32">
            @if ($project->number)
                <p class="text-xs uppercase tracking-widest text-neutral-500">
                    Project {{ str_pad($project->number, 2, '0', STR_PAD_LEFT) }}
                </p>
            @endif
            <h1 class="mt-4 text-4xl font-semibold md:text-6xl">{{ $project->name }}</h1>

            @if ($project->concept)
                <p class="mt-6 max-w-xl text-neutral-600">{{ $project->concept }}</p>
            @endif

            @if ($project->status === 'upcoming' && $project->opens_at)
                <p class="mt-6 text-sm text-neutral-600">
                    Dibuka {{ $project->opens_at->translatedFormat('j F Y, H.i') }}
                </p>
            @endif

            <a href="{{ route('shop') }}" wire:navigate
               class="mt-10 inline-block border border-black px-6 py-3 text-sm hover:bg-black hover:text-white">
                Lihat koleksi
            </a>
        </section>
    @endif

    <section class="mx-auto max-w-6xl px-6">
        <h2 class="mb-8 text-sm uppercase tracking-widest text-neutral-500">Pilihan</h2>
        <div class="grid grid-cols-2 gap-x-6 gap-y-12 md:grid-cols-4">
            @forelse ($featured as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="col-span-full text-neutral-500">Koleksi akan segera hadir.</p>
            @endforelse
        </div>
    </section>
</div>