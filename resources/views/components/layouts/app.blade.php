<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' | Emptiness Studio' : 'Emptiness Studio' }}</title>
    <meta name="description" content="Creating meaning in the space between.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-neutral-900 antialiased">
    <header class="border-b border-neutral-200">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
            <a href="{{ route('home') }}" wire:navigate class="text-sm font-semibold tracking-wide">Emptiness Studio</a>
            <nav class="flex gap-8 text-sm text-neutral-600">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-black">Home</a>
                <a href="{{ route('shop') }}" wire:navigate class="hover:text-black">Shop</a>
                <livewire:cart-count />
            </nav>
        </div>
    </header>

    <main>{{ $slot }}</main>

    <footer class="mt-32 border-t border-neutral-200">
        <div class="mx-auto max-w-6xl px-6 py-10 text-sm text-neutral-500">
            <p>Creating meaning in the space between.</p>
            <p class="mt-2">&copy; {{ date('Y') }} Emptiness Studio</p>
        </div>
    </footer>
</body>
</html>