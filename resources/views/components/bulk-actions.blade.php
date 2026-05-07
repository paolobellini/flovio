@props([
    'count' => 'selected.length',
])

<div
    x-show="{{ $count }} > 0"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="translate-y-4 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-4 opacity-0"
    x-cloak
    class="fixed inset-x-0 bottom-6 z-50 mx-auto w-fit"
>
    <div class="flex items-center gap-4 rounded-full border border-zinc-200/80 bg-white/90 px-5 py-2.5 shadow-xl shadow-zinc-900/8 backdrop-blur-sm">
        <flux:text class="text-sm font-medium" x-text="{{ $count }} + ' {{ __('selected') }}'"></flux:text>

        <flux:separator vertical class="h-6" />

        {{ $slot }}

        <flux:separator vertical class="h-6" />

        <flux:button variant="ghost" size="sm" icon="x-mark" x-on:click="selected = []">{{ __('Clear') }}</flux:button>
    </div>
</div>
