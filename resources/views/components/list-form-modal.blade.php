@props([
    'editing' => false,
])

@php
    $icons = ['envelope', 'megaphone', 'star', 'sparkles', 'calendar-days', 'arrow-path', 'users', 'heart'];
    $colors = [
        'zinc' => 'bg-zinc-400',
        'wine' => 'bg-wine-500',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'amber' => 'bg-amber-400',
        'orange' => 'bg-orange-500',
        'purple' => 'bg-purple-500',
    ];
@endphp

<flux:modal name="list-form" class="max-w-md md:min-w-md">
    <form wire:submit="save" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ $editing ? __('Edit list') : __('Create list') }}</flux:heading>
            <flux:subheading>{{ $editing ? __('Update the list details.') : __('Create a new list to organize your contacts.') }}</flux:subheading>
        </div>

        <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus />

        <flux:textarea wire:model="description" :label="__('Description')" :placeholder="__('What is this list for?')" rows="3" />

        {{-- Icon picker --}}
        <div x-data="{ selected: $wire.entangle('icon') }">
            <flux:label>{{ __('Icon') }}</flux:label>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach ($icons as $icon)
                    <button
                        type="button"
                        x-on:click="selected = '{{ $icon }}'"
                        x-bind:class="selected === '{{ $icon }}'
                            ? 'bg-wine-100 text-wine-700 ring-2 ring-wine-400'
                            : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200'"
                        class="flex h-10 w-10 items-center justify-center rounded-xl transition"
                    >
                        <flux:icon :name="$icon" variant="mini" class="size-5" />
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Color picker --}}
        <div x-data="{ selected: $wire.entangle('color') }">
            <flux:label>{{ __('Color') }}</flux:label>
            <div class="mt-2 flex flex-wrap gap-2.5">
                @foreach ($colors as $colorKey => $colorClass)
                    <button
                        type="button"
                        x-on:click="selected = '{{ $colorKey }}'"
                        x-bind:class="selected === '{{ $colorKey }}'
                            ? 'ring-2 ring-offset-2 ring-zinc-900'
                            : 'hover:scale-110'"
                        class="h-8 w-8 rounded-full transition {{ $colorClass }}"
                    >
                        <span class="sr-only">{{ $colorKey }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <flux:modal.close>
                <flux:button variant="filled" class="w-full">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" type="submit" class="w-full">
                {{ $editing ? __('Save') : __('Create list') }}
            </flux:button>
        </div>
    </form>
</flux:modal>
