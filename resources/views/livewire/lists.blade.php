<div class="flex h-full w-full flex-1 flex-col gap-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Lists') }}</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ __('Organize your contacts into targeted groups for campaigns.') }}</p>
        </div>
        <flux:button variant="primary" icon="plus">{{ __('Create list') }}</flux:button>
    </div>

    {{-- Stats cards --}}
    <div class="grid gap-5 sm:grid-cols-3">
        <x-stat-card icon="rectangle-stack" :label="__('Total lists')" value="6" color="wine" />
        <x-stat-card icon="users" :label="__('Total members')" value="3,482" color="green" />
        <x-stat-card icon="clock" :label="__('Last updated')" value="2h" />
    </div>

    {{-- Search --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="flex-1">
            <flux:input icon="magnifying-glass" :placeholder="__('Search lists...')" class="max-w-sm" />
        </div>
    </div>

    {{-- Lists grid --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @php
            $colorMap = [
                'wine' => ['bar' => 'from-wine-400 to-wine-300', 'hover' => 'hover:border-wine-200 hover:shadow-wine-100/20', 'bg' => 'bg-wine-50', 'text' => 'text-wine-600'],
                'blue' => ['bar' => 'from-blue-400 to-blue-300', 'hover' => 'hover:border-blue-200 hover:shadow-blue-100/20', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                'amber' => ['bar' => 'from-amber-400 to-amber-300', 'hover' => 'hover:border-amber-200 hover:shadow-amber-100/20', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
                'orange' => ['bar' => 'from-orange-400 to-orange-300', 'hover' => 'hover:border-orange-200 hover:shadow-orange-100/20', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                'green' => ['bar' => 'from-green-400 to-green-300', 'hover' => 'hover:border-green-200 hover:shadow-green-100/20', 'bg' => 'bg-green-50', 'text' => 'text-green-600'],
                'purple' => ['bar' => 'from-purple-400 to-purple-300', 'hover' => 'hover:border-purple-200 hover:shadow-purple-100/20', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
            ];
        @endphp

        @foreach ([
            ['name' => 'Newsletter', 'description' => 'Iscritti alla newsletter settimanale', 'count' => 1248, 'color' => 'wine', 'icon' => 'envelope', 'updated' => '2h fa', 'id' => 1],
            ['name' => 'Product Updates', 'description' => 'Aggiornamenti su nuovi prodotti e funzionalità', 'count' => 892, 'color' => 'blue', 'icon' => 'megaphone', 'updated' => '1g fa', 'id' => 2],
            ['name' => 'VIP Customers', 'description' => 'Clienti premium con alto tasso di engagement', 'count' => 156, 'color' => 'amber', 'icon' => 'star', 'updated' => '3g fa', 'id' => 3],
            ['name' => 'Re-engagement', 'description' => 'Contatti inattivi da più di 60 giorni', 'count' => 324, 'color' => 'orange', 'icon' => 'arrow-path', 'updated' => '5g fa', 'id' => 4],
            ['name' => 'New Subscribers', 'description' => 'Iscritti negli ultimi 30 giorni', 'count' => 89, 'color' => 'green', 'icon' => 'sparkles', 'updated' => '12h fa', 'id' => 5],
            ['name' => 'Events', 'description' => 'Partecipanti a eventi e degustazioni', 'count' => 773, 'color' => 'purple', 'icon' => 'calendar-days', 'updated' => '2g fa', 'id' => 6],
        ] as $list)
            @php $colors = $colorMap[$list['color']]; @endphp

            <a href="{{ route('lists.show', $list['id']) }}" wire:navigate class="group block rounded-2xl border border-zinc-200/80 bg-white transition-all duration-200 {{ $colors['hover'] }} hover:shadow-lg">
                {{-- Color top bar --}}
                <div class="h-1.5 rounded-t-2xl bg-gradient-to-r {{ $colors['bar'] }}"></div>

                <div class="p-5">
                    {{-- Header row --}}
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $colors['bg'] }}">
                            <flux:icon :name="$list['icon']" variant="mini" class="size-4.5 {{ $colors['text'] }}" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="truncate font-semibold text-zinc-900">{{ $list['name'] }}</h3>
                                <div class="ms-2 flex shrink-0 items-center gap-1">
                                    <flux:tooltip content="{{ __('Edit') }}" position="top">
                                        <button class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-zinc-100 hover:text-wine-800" onclick="event.preventDefault()">
                                            <flux:icon.pencil-square variant="mini" class="size-3.5" />
                                        </button>
                                    </flux:tooltip>
                                    <flux:tooltip content="{{ __('Delete') }}" position="top">
                                        <button class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-red-50 hover:text-red-600" onclick="event.preventDefault()">
                                            <flux:icon.trash variant="mini" class="size-3.5" />
                                        </button>
                                    </flux:tooltip>
                                </div>
                            </div>
                            <p class="mt-0.5 text-sm text-zinc-400 line-clamp-1">{{ $list['description'] }}</p>
                        </div>
                    </div>

                    {{-- Stats row --}}
                    <div class="mt-4 flex items-center gap-4">
                        <div class="flex items-center gap-1.5 rounded-lg bg-zinc-50 px-2.5 py-1.5 text-xs">
                            <flux:icon.users variant="mini" class="size-3.5 text-zinc-400" />
                            <span class="font-semibold tabular-nums text-zinc-700">{{ number_format($list['count']) }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                            <flux:icon.clock variant="mini" class="size-3.5" />
                            <span>{{ $list['updated'] }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

</div>
