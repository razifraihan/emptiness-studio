<div class="mx-auto max-w-3xl px-6 py-16 md:py-24">
    <h1 class="text-4xl font-semibold md:text-5xl">{{ $page->title }}</h1>

    @if ($page->intro)
        <p class="mt-6 text-lg italic text-neutral-600">{{ $page->intro }}</p>
    @endif

    @if ($page->body)
        @php
            $blocks = preg_split('/\R\s*\R/', trim($page->body));
        @endphp

        <div class="mt-12 space-y-6 leading-relaxed text-neutral-700">
            @foreach ($blocks as $block)
                @if (str_starts_with($block, '## '))
                    <h2 class="pt-8 text-sm uppercase tracking-widest text-neutral-500">{{ trim(substr($block, 3)) }}</h2>
                @else
                    <p class="whitespace-pre-line">{{ $block }}</p>
                @endif
            @endforeach
        </div>
    @endif
</div>
