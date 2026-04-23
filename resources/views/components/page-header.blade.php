@props([
    'heading' => '',
    'description' => '',
])

<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ $heading }}</h1>
        <p class="mt-1 text-sm text-zinc-500">{{ $description }}</p>
    </div>

    @if ($slot->isNotEmpty())
        <div>{{ $slot }}</div>
    @endif
</div>
