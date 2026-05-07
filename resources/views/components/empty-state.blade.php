@props([
    'icon' => 'inbox',
    'heading' => '',
    'description' => '',
])

<div {{ $attributes->class('flex flex-col items-center justify-center py-16') }}>
    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-zinc-100 to-zinc-50 shadow-sm">
        <flux:icon :name="$icon" variant="outline" class="size-8 text-zinc-400" />
    </div>
    <flux:heading size="sm" class="mt-5">{{ $heading }}</flux:heading>
    <flux:text variant="subtle" class="mt-1.5">{{ $description }}</flux:text>

    @if ($slot->isNotEmpty())
        <div class="mt-5">
            {{ $slot }}
        </div>
    @endif
</div>
