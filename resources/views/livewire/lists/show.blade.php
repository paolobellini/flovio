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

    {{-- AI Insight --}}
    <div class="relative overflow-hidden rounded-2xl border border-wine-200/60 bg-gradient-to-br from-wine-50 to-white p-5">
        <div class="flex items-start gap-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-wine-100 text-wine-700">
                <flux:icon.sparkles variant="mini" class="size-5" />
            </div>
            <div class="flex-1">
                <flux:heading size="sm" class="text-wine-900">{{ __('AI Insight') }}</flux:heading>
                <flux:text class="mt-1 leading-relaxed text-wine-800/80">
                    {{ __('This list has :members members with a :rate open rate, :diff above your account average. Best engagement on Tuesday and Thursday mornings. :inactive members haven\'t opened in 30+ days — consider moving them to a re-engagement list.', [
                        'members' => number_format($this->stats['members']),
                        'rate' => '68%',
                        'diff' => '15%',
                        'inactive' => 12,
                    ]) }}
                </flux:text>
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
                        <flux:input wire:model.live.debounce.300ms="memberSearch" icon="magnifying-glass" :placeholder="__('Search...')" size="sm" class="w-48" />
                        <flux:button variant="primary" icon="plus" size="sm" wire:click="openAddMembers">{{ __('Add') }}</flux:button>
                    </div>
                </div>

                @if ($this->members->isNotEmpty())
                    <div class="divide-y divide-zinc-100">
                        @foreach ($this->members as $contact)
                            <div class="flex items-center gap-4 px-5 py-3.5 transition-colors hover:bg-zinc-50/80">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-wine-100 to-wine-200 text-xs font-bold text-wine-700 ring-2 ring-white">
                                    {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium text-zinc-900">{{ $contact->name }}</p>
                                    <p class="truncate text-xs text-zinc-400">{{ $contact->email }}</p>
                                </div>
                                <flux:badge :color="$contact->status->color()" size="sm">
                                    {{ $contact->status->label() }}
                                </flux:badge>
                                <span class="hidden whitespace-nowrap text-xs text-zinc-400 sm:block">{{ $contact->pivot->created_at->translatedFormat('d M Y') }}</span>
                                <div class="flex items-center gap-1">
                                    <flux:tooltip content="{{ __('View') }}" position="top">
                                        <a href="{{ route('contacts.show', $contact) }}" wire:navigate class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-zinc-100 hover:text-wine-800">
                                            <flux:icon.eye variant="mini" class="size-3.5" />
                                        </a>
                                    </flux:tooltip>
                                    <flux:tooltip content="{{ __('Remove') }}" position="top">
                                        <button wire:click="removeMember({{ $contact->id }})" class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-red-50 hover:text-red-600">
                                            <flux:icon.x-mark variant="mini" class="size-3.5" />
                                        </button>
                                    </flux:tooltip>
                                </div>
                            </div>
                        @endforeach
                    </div>
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

    {{-- Add members modal --}}
    <flux:modal name="add-members" class="max-w-md md:min-w-md">
        <div class="space-y-5">
            <div>
                <flux:heading size="lg">{{ __('Add members') }}</flux:heading>
                <flux:subheading>{{ __('Select contacts to add to this list.') }}</flux:subheading>
            </div>

            <flux:input wire:model.live.debounce.300ms="addSearch" icon="magnifying-glass" :placeholder="__('Search contacts...')" size="sm" />

            <div class="max-h-64 overflow-y-auto divide-y divide-zinc-100 rounded-xl border border-zinc-200">
                @forelse ($this->availableContacts as $contact)
                    <label class="flex cursor-pointer items-center gap-3 px-4 py-3 transition-colors hover:bg-zinc-50">
                        <flux:checkbox wire:model="selectedContacts" value="{{ $contact->id }}" />
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-wine-100 to-wine-200 text-xs font-bold text-wine-700">
                                {{ mb_strtoupper(mb_substr($contact->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-zinc-900">{{ $contact->name }}</p>
                                <p class="truncate text-xs text-zinc-400">{{ $contact->email }}</p>
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="px-4 py-8 text-center">
                        <flux:text variant="subtle">{{ __('No contacts available.') }}</flux:text>
                    </div>
                @endforelse
            </div>

            <div class="flex gap-3">
                <flux:modal.close>
                    <flux:button variant="filled" class="w-full">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" class="w-full" wire:click="addMembers">
                    {{ __('Add selected') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Edit list modal --}}
    <x-list-form-modal :editing="true" />

    {{-- Delete confirmation modal --}}
    <x-confirm-delete
        name="confirm-delete-list"
        :heading="__('Delete list')"
        :description="__('Are you sure you want to delete this list? All member associations will be removed. This action cannot be undone.')"
    />
</div>
