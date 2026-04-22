@props([
    'icon' => 'chart-bar',
    'label' => '',
    'value' => '0',
    'color' => 'zinc',
])

@php
    $colors = match ($color) {
        'wine' => 'hover:border-wine-200 from-wine-100 to-wine-50 text-wine-600 shadow-wine-200/50 from-wine-100/80 to-wine-50/40',
        'green' => 'hover:border-green-200 from-green-100 to-green-50 text-green-600 shadow-green-200/50 from-green-100/80 to-green-50/40',
        default => 'hover:border-zinc-300 from-zinc-100 to-zinc-50 text-zinc-500 shadow-zinc-200/50 from-zinc-200/60 to-zinc-100/40',
    };
@endphp

<div class="group relative overflow-hidden rounded-2xl border border-zinc-200/80 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-0.5 {{ str_contains($colors, 'hover:border') ? explode(' ', $colors)[0] : '' }} hover:shadow-md">
    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br {{ $color === 'wine' ? 'from-wine-100/80 to-wine-50/40' : ($color === 'green' ? 'from-green-100/80 to-green-50/40' : 'from-zinc-200/60 to-zinc-100/40') }} opacity-0 blur-sm transition-opacity duration-300 group-hover:opacity-100"></div>
    <div class="relative">
        <div class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br {{ $color === 'wine' ? 'from-wine-100 to-wine-50 text-wine-600 shadow-wine-200/50' : ($color === 'green' ? 'from-green-100 to-green-50 text-green-600 shadow-green-200/50' : 'from-zinc-100 to-zinc-50 text-zinc-500 shadow-zinc-200/50') }} shadow-sm">
                <flux:icon :name="$icon" variant="mini" class="size-4" />
            </div>
            <flux:text variant="subtle" class="text-xs font-semibold uppercase tracking-wider">{{ $label }}</flux:text>
        </div>
        <p class="mt-4 text-3xl font-bold tabular-nums text-zinc-900" wire:loading.class="opacity-40" wire:target="search, status">
            {{ $value }}
        </p>
    </div>
</div>
