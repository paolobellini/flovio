@php
    $colorMap = [
        'wine' => ['bar' => 'from-wine-400 to-wine-300', 'bg' => 'bg-wine-50', 'text' => 'text-wine-600'],
        'blue' => ['bar' => 'from-blue-400 to-blue-300', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'amber' => ['bar' => 'from-amber-400 to-amber-300', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'orange' => ['bar' => 'from-orange-400 to-orange-300', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
        'green' => ['bar' => 'from-green-400 to-green-300', 'bg' => 'bg-green-50', 'text' => 'text-green-600'],
        'purple' => ['bar' => 'from-purple-400 to-purple-300', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
        'zinc' => ['bar' => 'from-zinc-400 to-zinc-300', 'bg' => 'bg-zinc-100', 'text' => 'text-zinc-500'],
    ];
    $colors = $colorMap[$list->color] ?? $colorMap['zinc'];
@endphp

<div class="flex h-full w-full flex-1 flex-col gap-8">
    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <flux:button variant="ghost" icon="arrow-left" :href="route('lists.index')" wire:navigate>
            {{ __('Lists') }}
        </flux:button>

        <div class="flex items-center gap-2">
            <flux:button variant="ghost" icon="trash" size="sm" class="text-red-600 hover:text-red-700" wire:click="confirmDelete">{{ __('Delete') }}</flux:button>
        </div>
    </div>

    {{-- List header --}}
    <div class="rounded-2xl border border-zinc-200/80 bg-white shadow-sm">
        <div class="h-1.5 rounded-t-2xl bg-gradient-to-r {{ $colors['bar'] }}"></div>
        <div class="p-6">
            <div class="flex items-start gap-5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $colors['bg'] }}">
                    <flux:icon :name="$list->icon" variant="mini" class="size-5 {{ $colors['text'] }}" />
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h1 class="text-xl font-bold tracking-tight text-zinc-900">{{ $list->name }}</h1>
                                @if ($list->is_ai_generated)
                                    <flux:badge color="purple" size="sm">{{ __('AI') }}</flux:badge>
                                @endif
                            </div>
                            @if ($list->description)
                                <p class="mt-1 text-sm text-zinc-500">{{ $list->description }}</p>
                            @endif
                        </div>
                        <flux:button variant="ghost" icon="pencil-square" size="sm" wire:click="edit">{{ __('Edit') }}</flux:button>
                    </div>

                    <div class="mt-3 flex items-center gap-4">
                        <flux:text variant="subtle" class="flex items-center gap-1.5 text-sm">
                            <flux:icon.users variant="mini" class="size-4" />
                            {{ __(':count contacts', ['count' => number_format($this->stats['members'])]) }}
                        </flux:text>
                        <flux:text variant="subtle" class="flex items-center gap-1.5 text-sm">
                            <flux:icon.calendar variant="mini" class="size-4" />
                            {{ __('Created :date', ['date' => $list->created_at->translatedFormat('d M Y')]) }}
                        </flux:text>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid gap-5 sm:grid-cols-4">
        <x-stat-card icon="users" :label="__('Members')" :value="number_format($this->stats['members'])" color="wine" />
        <x-stat-card icon="envelope" :label="__('Campaigns sent')" :value="(string) $this->stats['campaigns_sent']" color="green" />
        <x-stat-card icon="envelope-open" :label="__('Avg. open rate')" :value="$this->stats['avg_open_rate']" />
        <x-stat-card icon="cursor-arrow-rays" :label="__('Avg. click rate')" :value="$this->stats['avg_click_rate']" />
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

                @if ($list->contacts()->count() > 0)
                    <flux:table>
                        <flux:table.rows>
                            @foreach ($list->contacts()->latest('contact_mailing_list.created_at')->limit(5)->get() as $contact)
                                <flux:table.row class="group/row transition-colors hover:bg-zinc-50/80">
                                    <flux:table.cell>
                                        <div class="flex items-center gap-3 ps-2">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-wine-100 to-wine-200 text-xs font-bold text-wine-700 ring-2 ring-white">
                                                {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate font-medium text-zinc-900">{{ $contact->name }}</p>
                                                <p class="truncate text-xs text-zinc-400">{{ $contact->email }}</p>
                                            </div>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge :color="$contact->status->color()" size="sm" inset="top bottom">
                                            {{ $contact->status->label() }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell class="whitespace-nowrap text-sm text-zinc-400">
                                        {{ $contact->pivot->created_at->translatedFormat('d M Y') }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <div class="flex items-center gap-1 pe-2">
                                            <flux:tooltip content="{{ __('View') }}" position="top">
                                                <a href="{{ route('contacts.show', $contact) }}" wire:navigate class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-zinc-100 hover:text-wine-800">
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
                @else
                    <x-empty-state
                        icon="users"
                        :heading="__('No members yet.')"
                        :description="__('Add contacts to this list to get started.')"
                        class="py-10"
                    />
                @endif
            </div>
        </div>

        {{-- Activity --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl border border-zinc-200/80 bg-white shadow-sm">
                <div class="border-b border-zinc-100 px-5 py-4">
                    <flux:heading size="sm">{{ __('Recent campaigns') }}</flux:heading>
                </div>
                <x-empty-state
                    icon="megaphone"
                    :heading="__('No campaigns yet.')"
                    :description="__('Campaigns sent to this list will appear here.')"
                    class="py-10"
                />
            </div>
        </div>
    </div>

    {{-- Edit list modal --}}
    <x-list-form-modal :editing="true" />

    {{-- Delete confirmation modal --}}
    <x-confirm-delete
        name="confirm-delete-list"
        :heading="__('Delete list')"
        :description="__('Are you sure you want to delete this list? All member associations will be removed. This action cannot be undone.')"
    />
</div>
