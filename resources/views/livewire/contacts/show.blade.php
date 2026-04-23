<div class="flex h-full w-full flex-1 flex-col gap-6">
    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <flux:button variant="ghost" icon="arrow-left" :href="route('contacts.index')" wire:navigate>
            {{ __('Contacts') }}
        </flux:button>

        <div class="flex items-center gap-2">
            <flux:button variant="ghost" icon="pencil-square" size="sm">{{ __('Edit') }}</flux:button>
            <flux:button variant="ghost" icon="trash" size="sm" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</flux:button>
        </div>
    </div>

    {{-- Contact header card --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-6">
        <div class="flex items-start gap-5">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-wine-100 text-lg font-semibold text-wine-800">
                {{ mb_strtoupper(mb_substr($contact->name, 0, 2)) }}
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-bold tracking-tight text-zinc-900">{{ $contact->name }}</h1>
                    <flux:badge :color="$contact->status->color()" size="sm">{{ $contact->status->label() }}</flux:badge>
                </div>
                <div class="mt-1 flex items-center gap-4">
                    <flux:text variant="subtle" class="flex items-center gap-1.5">
                        <flux:icon.envelope variant="mini" class="size-4" />
                        {{ $contact->email }}
                    </flux:text>
                    <flux:text variant="subtle" class="flex items-center gap-1.5">
                        <flux:icon.calendar variant="mini" class="size-4" />
                        {{ __('Added :date', ['date' => $contact->created_at->translatedFormat('d M Y')]) }}
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

    {{-- AI Summary --}}
    <div class="relative overflow-hidden rounded-xl border border-wine-200/60 bg-gradient-to-br from-wine-50 to-white p-5">
        <div class="flex items-start gap-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-wine-100 text-wine-700">
                <flux:icon.sparkles variant="mini" class="size-5" />
            </div>
            <div class="flex-1">
                <flux:heading size="sm" class="text-wine-900">{{ __('AI Insight') }}</flux:heading>
                <flux:text class="mt-1 leading-relaxed text-wine-800/80">
                    {{ __(':name is a highly engaged subscriber with a :rate open rate, well above the :avg average. Most active on product update campaigns — a strong candidate for the VIP list. Last interaction was :days ago.', [
                        'name' => $contact->name,
                        'rate' => '75%',
                        'avg' => '42%',
                        'days' => 2,
                    ]) }}
                </flux:text>
            </div>
        </div>
    </div>

    {{-- Stats cards --}}
    <div class="grid gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Emails sent') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">24</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Opened') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">18</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Clicked') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">7</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Open rate') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">75%</p>
        </div>
    </div>

    {{-- Two columns: Lists + Activity --}}
    <div class="grid gap-6 lg:grid-cols-5">
        {{-- Lists --}}
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-zinc-200 bg-white">
                <div class="flex items-center justify-between border-b border-zinc-100 px-5 py-4">
                    <flux:heading size="sm">{{ __('Lists') }}</flux:heading>
                    <flux:button variant="ghost" icon="plus" size="sm" wire:click="openAddToList">{{ __('Add') }}</flux:button>
                </div>
                @if ($this->contactLists->isNotEmpty())
                    <div class="divide-y divide-zinc-100">
                        @foreach ($this->contactLists as $list)
                            <div class="flex items-center justify-between px-5 py-3">
                                <a href="{{ route('lists.show', $list) }}" wire:navigate class="flex items-center gap-3 transition-colors hover:text-wine-800">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-100">
                                        <flux:icon :name="$list->icon" variant="mini" class="size-4 text-zinc-500" />
                                    </div>
                                    <flux:text class="font-medium">{{ $list->name }}</flux:text>
                                </a>
                                <div class="flex items-center gap-2">
                                    <flux:badge size="sm" color="zinc" inset="top bottom">{{ number_format($list->contacts_count) }}</flux:badge>
                                    <flux:tooltip content="{{ __('Remove') }}" position="top">
                                        <button wire:click="removeFromList({{ $list->id }})" class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-red-50 hover:text-red-600">
                                            <flux:icon.x-mark variant="mini" class="size-3.5" />
                                        </button>
                                    </flux:tooltip>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state
                        icon="rectangle-stack"
                        :heading="__('No lists yet.')"
                        :description="__('Add this contact to a list.')"
                        class="py-8"
                    />
                @endif
            </div>
        </div>

        {{-- Activity --}}
        <div class="lg:col-span-3">
            <div class="rounded-xl border border-zinc-200 bg-white">
                <div class="border-b border-zinc-100 px-5 py-4">
                    <flux:heading size="sm">{{ __('Recent activity') }}</flux:heading>
                </div>
                <div class="divide-y divide-zinc-100">
                    @foreach ([
                        ['icon' => 'envelope', 'color' => 'text-blue-600 bg-blue-100', 'text' => 'Newsletter di aprile', 'action' => 'sent', 'date' => '22 apr 2026, 10:30'],
                        ['icon' => 'envelope-open', 'color' => 'text-green-600 bg-green-100', 'text' => 'Newsletter di aprile', 'action' => 'opened', 'date' => '22 apr 2026, 11:15'],
                        ['icon' => 'cursor-arrow-rays', 'color' => 'text-purple-600 bg-purple-100', 'text' => 'Newsletter di aprile', 'action' => 'clicked', 'date' => '22 apr 2026, 11:22'],
                        ['icon' => 'envelope', 'color' => 'text-blue-600 bg-blue-100', 'text' => 'Promo primavera', 'action' => 'sent', 'date' => '15 apr 2026, 09:00'],
                        ['icon' => 'envelope-open', 'color' => 'text-green-600 bg-green-100', 'text' => 'Promo primavera', 'action' => 'opened', 'date' => '15 apr 2026, 09:45'],
                        ['icon' => 'envelope', 'color' => 'text-blue-600 bg-blue-100', 'text' => 'Newsletter di marzo', 'action' => 'sent', 'date' => '20 mar 2026, 10:00'],
                    ] as $activity)
                        <div class="flex items-start gap-3 px-5 py-3.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $activity['color'] }}">
                                <flux:icon :name="$activity['icon']" variant="mini" class="size-4" />
                            </div>
                            <div class="flex-1">
                                <flux:text>
                                    <span class="font-medium">{{ $activity['text'] }}</span>
                                    <span class="text-zinc-400">&mdash;</span>
                                    {{ __($activity['action']) }}
                                </flux:text>
                                <flux:text variant="subtle" class="text-xs">{{ $activity['date'] }}</flux:text>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Add to list modal --}}
    <flux:modal name="add-to-list" class="max-w-md md:min-w-md">
        <div class="space-y-5">
            <div>
                <flux:heading size="lg">{{ __('Add to list') }}</flux:heading>
                <flux:subheading>{{ __('Select lists to add this contact to.') }}</flux:subheading>
            </div>

            <flux:input wire:model.live.debounce.300ms="listSearch" icon="magnifying-glass" :placeholder="__('Search lists...')" size="sm" />

            <div class="max-h-64 divide-y divide-zinc-100 overflow-y-auto rounded-xl border border-zinc-200">
                @forelse ($this->availableLists as $list)
                    <label class="flex cursor-pointer items-center gap-3 px-4 py-3 transition-colors hover:bg-zinc-50">
                        <flux:checkbox wire:model="selectedLists" value="{{ $list->id }}" />
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-zinc-100">
                                <flux:icon :name="$list->icon" variant="mini" class="size-4 text-zinc-500" />
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-zinc-900">{{ $list->name }}</p>
                                @if ($list->description)
                                    <p class="truncate text-xs text-zinc-400">{{ $list->description }}</p>
                                @endif
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="px-4 py-8 text-center">
                        <flux:text variant="subtle">{{ __('No lists available.') }}</flux:text>
                    </div>
                @endforelse
            </div>

            <div class="flex gap-3">
                <flux:modal.close>
                    <flux:button variant="filled" class="w-full">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" class="w-full" wire:click="addToLists">
                    {{ __('Add selected') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
