<div class="flex h-full w-full flex-1 flex-col gap-8">
    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <flux:button variant="ghost" icon="arrow-left" :href="route('lists.index')" wire:navigate>
            {{ __('Lists') }}
        </flux:button>

        <div class="flex items-center gap-2">
            <flux:button variant="ghost" icon="trash" size="sm" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</flux:button>
        </div>
    </div>

    {{-- List header --}}
    <div class="rounded-2xl border border-zinc-200/80 bg-white shadow-sm">
        <div class="h-1.5 rounded-t-2xl bg-gradient-to-r from-wine-400 to-wine-300"></div>
        <div class="p-6">
            <div class="flex items-start gap-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-wine-50">
                    <flux:icon.envelope variant="mini" class="size-5 text-wine-600" />
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-bold tracking-tight text-zinc-900">Newsletter</h1>
                            <p class="mt-1 text-sm text-zinc-500">Iscritti alla newsletter settimanale</p>
                        </div>
                        <flux:button variant="ghost" icon="pencil-square" size="sm">{{ __('Edit') }}</flux:button>
                    </div>

                    <div class="mt-3 flex items-center gap-4">
                        <flux:text variant="subtle" class="flex items-center gap-1.5 text-sm">
                            <flux:icon.users variant="mini" class="size-4" />
                            {{ __(':count contacts', ['count' => '1,248']) }}
                        </flux:text>
                        <flux:text variant="subtle" class="flex items-center gap-1.5 text-sm">
                            <flux:icon.calendar variant="mini" class="size-4" />
                            {{ __('Created :date', ['date' => '15 mar 2026']) }}
                        </flux:text>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid gap-5 sm:grid-cols-4">
        <x-stat-card icon="users" :label="__('Members')" value="1,248" color="wine" />
        <x-stat-card icon="envelope" :label="__('Campaigns sent')" value="12" color="green" />
        <x-stat-card icon="envelope-open" :label="__('Avg. open rate')" value="68%" />
        <x-stat-card icon="cursor-arrow-rays" :label="__('Avg. click rate')" value="24%" />
    </div>

    {{-- Two columns: Members + Activity --}}
    <div class="grid gap-6 lg:grid-cols-5">
        {{-- Members --}}
        <div class="lg:col-span-3">
            <div class="rounded-2xl border border-zinc-200/80 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4">
                    <flux:heading size="sm">{{ __('Members') }}</flux:heading>
                    <div class="flex items-center gap-2">
                        <flux:input icon="magnifying-glass" :placeholder="__('Search...')" size="sm" class="w-48" />
                        <flux:button variant="primary" icon="plus" size="sm">{{ __('Add') }}</flux:button>
                    </div>
                </div>

                <flux:table>
                    <flux:table.rows>
                        @foreach ([
                            ['name' => 'Marco Rossi', 'email' => 'marco.rossi@example.com', 'status' => 'subscribed', 'added' => '22 apr 2026'],
                            ['name' => 'Laura Bianchi', 'email' => 'laura.bianchi@example.com', 'status' => 'subscribed', 'added' => '21 apr 2026'],
                            ['name' => 'Giovanni Verdi', 'email' => 'g.verdi@example.com', 'status' => 'unsubscribed', 'added' => '20 apr 2026'],
                            ['name' => 'Sofia Colombo', 'email' => 'sofia.colombo@example.com', 'status' => 'subscribed', 'added' => '19 apr 2026'],
                            ['name' => 'Alessandro Ricci', 'email' => 'a.ricci@example.com', 'status' => 'subscribed', 'added' => '18 apr 2026'],
                        ] as $member)
                            <flux:table.row class="group/row transition-colors hover:bg-zinc-50/80">
                                <flux:table.cell>
                                    <div class="flex items-center gap-3 ps-2">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-wine-100 to-wine-200 text-xs font-bold text-wine-700 ring-2 ring-white">
                                            {{ mb_strtoupper(mb_substr($member['name'], 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-medium text-zinc-900">{{ $member['name'] }}</p>
                                            <p class="truncate text-xs text-zinc-400">{{ $member['email'] }}</p>
                                        </div>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge
                                        :color="$member['status'] === 'subscribed' ? 'green' : 'zinc'"
                                        size="sm"
                                        inset="top bottom"
                                    >
                                        {{ $member['status'] === 'subscribed' ? __('Subscribed') : __('Unsubscribed') }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell class="whitespace-nowrap text-sm text-zinc-400">{{ $member['added'] }}</flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex items-center gap-1 pe-2">
                                        <flux:tooltip content="{{ __('View') }}" position="top">
                                            <a href="#" class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-zinc-100 hover:text-wine-800">
                                                <flux:icon.eye variant="mini" class="size-3.5" />
                                            </a>
                                        </flux:tooltip>
                                        <flux:tooltip content="{{ __('Remove') }}" position="top">
                                            <button class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-red-50 hover:text-red-600">
                                                <flux:icon.x-mark variant="mini" class="size-3.5" />
                                            </button>
                                        </flux:tooltip>
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

                {{-- Pagination --}}
                <div class="flex items-center justify-between border-t border-zinc-100 px-5 py-3">
                    <flux:text variant="subtle" class="text-xs">
                        {{ __('Showing :from to :to of :total contacts', ['from' => 1, 'to' => 5, 'total' => 1248]) }}
                    </flux:text>
                    <div class="flex items-center gap-1">
                        <flux:button variant="ghost" size="sm" icon="chevron-left" disabled />
                        <flux:button variant="ghost" size="sm" icon="chevron-right" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-zinc-200/80 bg-white shadow-sm">
                <div class="border-b border-zinc-100 px-5 py-4">
                    <flux:heading size="sm">{{ __('Recent campaigns') }}</flux:heading>
                </div>
                <div class="divide-y divide-zinc-100">
                    @foreach ([
                        ['name' => 'Newsletter di aprile', 'sent' => '22 apr 2026', 'opens' => '72%', 'clicks' => '28%', 'status' => 'sent'],
                        ['name' => 'Promo primavera', 'sent' => '15 apr 2026', 'opens' => '65%', 'clicks' => '22%', 'status' => 'sent'],
                        ['name' => 'Newsletter di marzo', 'sent' => '20 mar 2026', 'opens' => '70%', 'clicks' => '25%', 'status' => 'sent'],
                        ['name' => 'Welcome series #3', 'sent' => '10 mar 2026', 'opens' => '58%', 'clicks' => '15%', 'status' => 'sent'],
                    ] as $campaign)
                        <div class="px-5 py-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-medium text-zinc-900">{{ $campaign['name'] }}</p>
                                    <p class="mt-0.5 text-xs text-zinc-400">{{ $campaign['sent'] }}</p>
                                </div>
                                <flux:badge color="green" size="sm">{{ __('Sent') }}</flux:badge>
                            </div>
                            <div class="mt-3 flex items-center gap-4">
                                <div class="flex items-center gap-1.5 rounded-lg bg-zinc-50 px-2 py-1 text-xs">
                                    <flux:icon.envelope-open variant="mini" class="size-3 text-green-500" />
                                    <span class="font-medium text-zinc-600">{{ $campaign['opens'] }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 rounded-lg bg-zinc-50 px-2 py-1 text-xs">
                                    <flux:icon.cursor-arrow-rays variant="mini" class="size-3 text-purple-500" />
                                    <span class="font-medium text-zinc-600">{{ $campaign['clicks'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
