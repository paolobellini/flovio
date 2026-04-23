@php
    $colorMap = [
        'wine' => ['bar' => 'from-wine-400 to-wine-300', 'hover' => 'hover:border-wine-200 hover:shadow-wine-100/20', 'bg' => 'bg-wine-50', 'text' => 'text-wine-600'],
        'blue' => ['bar' => 'from-blue-400 to-blue-300', 'hover' => 'hover:border-blue-200 hover:shadow-blue-100/20', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'amber' => ['bar' => 'from-amber-400 to-amber-300', 'hover' => 'hover:border-amber-200 hover:shadow-amber-100/20', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'orange' => ['bar' => 'from-orange-400 to-orange-300', 'hover' => 'hover:border-orange-200 hover:shadow-orange-100/20', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
        'green' => ['bar' => 'from-green-400 to-green-300', 'hover' => 'hover:border-green-200 hover:shadow-green-100/20', 'bg' => 'bg-green-50', 'text' => 'text-green-600'],
        'purple' => ['bar' => 'from-purple-400 to-purple-300', 'hover' => 'hover:border-purple-200 hover:shadow-purple-100/20', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
        'zinc' => ['bar' => 'from-zinc-400 to-zinc-300', 'hover' => 'hover:border-zinc-300 hover:shadow-zinc-100/20', 'bg' => 'bg-zinc-100', 'text' => 'text-zinc-500'],
    ];
@endphp

<div class="flex h-full w-full flex-1 flex-col gap-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Lists') }}</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ __('Organize your contacts into targeted groups for campaigns.') }}</p>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="create">{{ __('Create list') }}</flux:button>
    </div>

    {{-- Stats cards --}}
    <div class="grid gap-5 sm:grid-cols-3">
        <x-stat-card icon="rectangle-stack" :label="__('Total lists')" :value="(string) $this->stats['total']" color="wine" />
        <x-stat-card icon="users" :label="__('Total members')" :value="number_format($this->stats['total_members'])" color="green" />
        <x-stat-card icon="clock" :label="__('Last updated')" :value="$this->stats['last_updated'] ?? '-'" />
    </div>

    {{-- Search --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('Search lists...')" class="max-w-sm" />
        </div>
    </div>

    {{-- Lists grid --}}
    @if ($this->lists->isNotEmpty())
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->lists as $list)
                @php $colors = $colorMap[$list->color] ?? $colorMap['zinc']; @endphp

                <a href="{{ route('lists.show', $list) }}" wire:navigate class="group block rounded-2xl border border-zinc-200/80 bg-white transition-all duration-200 {{ $colors['hover'] }} hover:shadow-lg">
                    {{-- Color top bar --}}
                    <div class="h-1.5 rounded-t-2xl bg-gradient-to-r {{ $colors['bar'] }}"></div>

                    <div class="p-5">
                        {{-- Header row --}}
                        <div class="flex items-start gap-3.5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $colors['bg'] }}">
                                <flux:icon :name="$list->icon" variant="mini" class="size-4.5 {{ $colors['text'] }}" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <h3 class="truncate font-semibold text-zinc-900">{{ $list->name }}</h3>
                                        @if ($list->is_ai_generated)
                                            <flux:badge color="purple" size="sm">{{ __('AI') }}</flux:badge>
                                        @endif
                                    </div>
                                    <div class="ms-2 flex shrink-0 items-center gap-1">
                                        <flux:tooltip content="{{ __('Delete') }}" position="top">
                                            <button class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-red-50 hover:text-red-600" onclick="event.preventDefault()">
                                                <flux:icon.trash variant="mini" class="size-3.5" />
                                            </button>
                                        </flux:tooltip>
                                    </div>
                                </div>
                                @if ($list->description)
                                    <p class="mt-0.5 text-sm text-zinc-400 line-clamp-1">{{ $list->description }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Stats row --}}
                        <div class="mt-4 flex items-center gap-4">
                            <div class="flex items-center gap-1.5 rounded-lg bg-zinc-50 px-2.5 py-1.5 text-xs">
                                <flux:icon.users variant="mini" class="size-3.5 text-zinc-400" />
                                <span class="font-semibold tabular-nums text-zinc-700">{{ number_format($list->contacts_count) }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                                <flux:icon.clock variant="mini" class="size-3.5" />
                                <span>{{ $list->updated_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <x-empty-state
            icon="rectangle-stack"
            :heading="__('No lists yet.')"
            :description="__('Create your first list to start organizing contacts.')"
        >
            <flux:button variant="primary" icon="plus" wire:click="create">{{ __('Create list') }}</flux:button>
        </x-empty-state>
    @endif

    {{-- Create list modal --}}
    <x-list-form-modal />
</div>
