<div
    class="flex h-full w-full flex-1 flex-col gap-6"
    x-data="{
        selected: [],
        get allSelected() { return this.pageIds.length > 0 && this.selected.length === this.pageIds.length },
        get someSelected() { return this.selected.length > 0 && this.selected.length < this.pageIds.length },
        get pageIds() { return Array.from(this.$root.querySelectorAll('[data-contact-id]')).map(el => Number(el.dataset.contactId)) },
        toggleAll() {
            this.selected = this.allSelected ? [] : [...this.pageIds]
        },
        toggle(id) {
            this.selected.includes(id) ? this.selected = this.selected.filter(i => i !== id) : this.selected.push(id)
        },
    }"
>
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Contacts') }}</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ __('Manage your email recipients and subscriber lists.') }}</p>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="create">{{ __('Add contact') }}</flux:button>
    </div>

    {{-- Stats cards --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Total contacts') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">{{ number_format($this->stats['total']) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Subscribed') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">{{ number_format($this->stats['subscribed']) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Unsubscribed') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">{{ number_format($this->stats['unsubscribed']) }}</p>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('Search contacts...')" class="max-w-sm" />
        </div>

        <div class="flex items-center gap-3">
            <flux:select wire:model.live="status" class="w-44" :placeholder="__('All statuses')">
                <flux:select.option value="">{{ __('All statuses') }}</flux:select.option>
                <flux:select.option value="subscribed">{{ __('Subscribed') }}</flux:select.option>
                <flux:select.option value="unsubscribed">{{ __('Unsubscribed') }}</flux:select.option>
            </flux:select>

            <flux:separator vertical class="my-auto h-6" />

            <flux:button variant="ghost" icon="arrow-up-tray">{{ __('Import') }}</flux:button>
        </div>
    </div>

    {{-- Contacts table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white">
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="w-16">
                    <div class="ps-4">
                        <flux:checkbox x-on:click="toggleAll()" x-bind:checked="allSelected" x-bind:indeterminate="someSelected" />
                    </div>
                </flux:table.column>
                <flux:table.column>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Email') }}</flux:table.column>
                <flux:table.column>{{ __('Status') }}</flux:table.column>
                <flux:table.column>{{ __('Added') }}</flux:table.column>
                <flux:table.column class="w-32"></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->contacts as $contact)
                    <flux:table.row :key="$contact->id" x-bind:class="selected.includes({{ $contact->id }}) && 'bg-zinc-50'" data-contact-id="{{ $contact->id }}">
                        <flux:table.cell>
                            <div class="ps-4">
                                <flux:checkbox x-on:click="toggle({{ $contact->id }})" x-bind:checked="selected.includes({{ $contact->id }})" />
                            </div>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $contact->name }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $contact->email }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge
                                :color="$contact->status->color()"
                                size="sm"
                                inset="top bottom"
                            >
                                {{ $contact->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $contact->created_at->translatedFormat('d M Y') }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-1.5 pe-2">
                                <flux:tooltip content="{{ __('View') }}" position="top">
                                    <a href="{{ route('contacts.show', $contact) }}" wire:navigate class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100 hover:text-wine-800">
                                        <flux:icon.eye variant="mini" class="size-4" />
                                    </a>
                                </flux:tooltip>
                                <flux:tooltip content="{{ __('Edit') }}" position="top">
                                    <button wire:click="edit({{ $contact->id }})" class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100 hover:text-wine-800">
                                        <flux:icon.pencil-square variant="mini" class="size-4" />
                                    </button>
                                </flux:tooltip>
                                <flux:tooltip content="{{ __('Delete') }}" position="top">
                                    <button wire:click="confirmDelete({{ $contact->id }})" class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-red-50 hover:text-red-600">
                                        <flux:icon.trash variant="mini" class="size-4" />
                                    </button>
                                </flux:tooltip>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center">
                            <flux:text variant="subtle">{{ __('No contacts found.') }}</flux:text>
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    {{-- Pagination --}}
    @if ($this->contacts->hasPages())
        <div class="flex items-center justify-between">
            <flux:text variant="subtle" class="text-sm">
                {{ __('Showing :from to :to of :total contacts', ['from' => $this->contacts->firstItem(), 'to' => $this->contacts->lastItem(), 'total' => $this->contacts->total()]) }}
            </flux:text>

            <div class="flex items-center gap-1">
                @if ($this->contacts->onFirstPage())
                    <flux:button variant="ghost" size="sm" icon="chevron-left" disabled />
                @else
                    <flux:button variant="ghost" size="sm" icon="chevron-left" wire:click="previousPage" />
                @endif

                @if ($this->contacts->hasMorePages())
                    <flux:button variant="ghost" size="sm" icon="chevron-right" wire:click="nextPage" />
                @else
                    <flux:button variant="ghost" size="sm" icon="chevron-right" disabled />
                @endif
            </div>
        </div>
    @endif

    {{-- Contact form modal --}}
    <flux:modal name="contact-form" class="max-w-md md:min-w-md">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editing ? __('Edit contact') : __('Add contact') }}</flux:heading>
                <flux:subheading>{{ $editing ? __('Update the contact details.') : __('Add a new contact to your list.') }}</flux:subheading>
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus />

            <flux:input wire:model="email" :label="__('Email')" type="email" required />

            <div class="flex gap-3 pt-2">
                <flux:modal.close>
                    <flux:button variant="filled" class="w-full">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ $editing ? __('Save') : __('Add contact') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Delete confirmation modal --}}
    <x-confirm-delete
        name="confirm-delete-contact"
        :heading="__('Delete contact')"
        :description="__('Are you sure you want to delete this contact? This action cannot be undone.')"
    />

    {{-- Bulk actions floating bar --}}
    <x-bulk-actions>
        <flux:button variant="ghost" size="sm" icon="trash">{{ __('Delete') }}</flux:button>
        <flux:button variant="ghost" size="sm" icon="arrow-down-tray">{{ __('Export') }}</flux:button>
    </x-bulk-actions>
</div>
