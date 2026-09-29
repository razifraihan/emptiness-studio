@php
    $groups = ['Collection' => $current, 'Archive' => $archive];
@endphp

<div class="mx-auto max-w-6xl px-6 py-16">
    <h1 class="text-3xl font-semibold">Project</h1>
    <p class="mt-3 max-w-xl text-neutral-600">The Emptiness Journey, one release at a time.</p>

    @foreach ($groups as $title => $items)
        @if ($items->isNotEmpty())
            <h2 class="mb-8 mt-16 text-sm uppercase tracking-widest text-neutral-500">{{ $title }}</h2>

            <div class="grid grid-cols-1 gap-x-6 gap-y-12 md:grid-cols-2">
                @foreach ($items as $p)
                    @php
                        $note = match (true) {
                            (bool) ($p->opens_at && $p->opens_at->isFuture()) => 'Opens ' . $p->opens_at->locale('en')->translatedFormat('j F Y, H:i'),
                            $p->is_open_for_sale => 'Now open',
                            default => 'Archive',
                        };
                    @endphp

                    <a href="{{ route('project.show', $p->slug) }}" wire:navigate class="group block" wire:key="project-{{ $p->id }}">
                        <div class="overflow-hidden bg-neutral-100">
                            @if ($p->cover_image)
                                <img src="{{ asset('storage/' . $p->cover_image) }}" alt="{{ $p->name }}" loading="lazy"
                                     class="aspect-[4/3] w-full object-cover">
                            @else
                                <div class="aspect-[4/3] w-full"></div>
                            @endif
                        </div>

                        <div class="mt-4">
                            @if ($p->number)
                                <p class="text-xs uppercase tracking-widest text-neutral-500">
                                    Project {{ str_pad($p->number, 2, '0', STR_PAD_LEFT) }}
                                </p>
                            @endif
                            <p class="mt-1 text-lg">{{ $p->name }}</p>
                            @if ($p->key_message)
                                <p class="mt-1 text-sm text-neutral-600">{{ $p->key_message }}</p>
                            @endif
                            <p class="mt-2 text-sm text-neutral-500">{{ $note }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach

    @if ($current->isEmpty() && $archive->isEmpty())
        <p class="mt-16 text-neutral-500">Projects are coming soon.</p>
    @endif
</div>