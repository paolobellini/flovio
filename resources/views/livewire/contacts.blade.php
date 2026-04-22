<div
    class="flex h-full w-full flex-1 flex-col gap-6"
    x-data="{
        ids: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        selected: [],
        get allSelected() { return this.ids.length > 0 && this.selected.length === this.ids.length },
        get someSelected() { return this.selected.length > 0 && this.selected.length < this.ids.length },
        toggleAll() {
            this.selected = this.allSelected ? [] : [...this.ids]
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
        <flux:button variant="primary" icon="plus">{{ __('Add contact') }}</flux:button>
    </div>

    {{-- Stats cards --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Total contacts') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">1,248</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Subscribed') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">1,102</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-5">
            <flux:text variant="subtle" class="text-xs uppercase tracking-wider">{{ __('Unsubscribed') }}</flux:text>
            <p class="mt-1 text-2xl font-semibold text-zinc-900">146</p>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="flex-1">
            <flux:input icon="magnifying-glass" :placeholder="__('Search contacts...')" class="max-w-sm" />
        </div>

        <div class="flex items-center gap-3">
            <flux:select class="w-44" :placeholder="__('All statuses')">
                <flux:select.option>{{ __('All statuses') }}</flux:select.option>
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
                <flux:table.column class="w-24"></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ([
                    ['id' => 1, 'name' => 'Marco Rossi', 'email' => 'marco.rossi@example.com', 'status' => 'subscribed', 'date' => '22 apr 2026'],
                    ['id' => 2, 'name' => 'Laura Bianchi', 'email' => 'laura.bianchi@example.com', 'status' => 'subscribed', 'date' => '21 apr 2026'],
                    ['id' => 3, 'name' => 'Giovanni Verdi', 'email' => 'g.verdi@example.com', 'status' => 'unsubscribed', 'date' => '20 apr 2026'],
                    ['id' => 4, 'name' => 'Sofia Colombo', 'email' => 'sofia.colombo@example.com', 'status' => 'subscribed', 'date' => '19 apr 2026'],
                    ['id' => 5, 'name' => 'Alessandro Ricci', 'email' => 'a.ricci@example.com', 'status' => 'subscribed', 'date' => '18 apr 2026'],
                    ['id' => 6, 'name' => 'Elena Moretti', 'email' => 'elena.moretti@example.com', 'status' => 'subscribed', 'date' => '17 apr 2026'],
                    ['id' => 7, 'name' => 'Luca Ferraro', 'email' => 'l.ferraro@example.com', 'status' => 'unsubscribed', 'date' => '16 apr 2026'],
                    ['id' => 8, 'name' => 'Giulia Romano', 'email' => 'giulia.romano@example.com', 'status' => 'subscribed', 'date' => '15 apr 2026'],
                    ['id' => 9, 'name' => 'Andrea Conti', 'email' => 'andrea.conti@example.com', 'status' => 'subscribed', 'date' => '14 apr 2026'],
                    ['id' => 10, 'name' => 'Chiara Esposito', 'email' => 'c.esposito@example.com', 'status' => 'subscribed', 'date' => '13 apr 2026'],
                ] as $contact)
                    <flux:table.row x-bind:class="selected.includes({{ $contact['id'] }}) && 'bg-zinc-50'">
                        <flux:table.cell>
                            <div class="ps-4">
                                <flux:checkbox x-on:click="toggle({{ $contact['id'] }})" x-bind:checked="selected.includes({{ $contact['id'] }})" />
                            </div>
                        </flux:table.cell>
                        <flux:table.cell variant="strong">{{ $contact['name'] }}</flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $contact['email'] }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge
                                :color="$contact['status'] === 'subscribed' ? 'green' : 'zinc'"
                                size="sm"
                                inset="top bottom"
                            >
                                {{ $contact['status'] === 'subscribed' ? __('Subscribed') : __('Unsubscribed') }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="whitespace-nowrap">{{ $contact['date'] }}</flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-1.5 pe-2">
                                <flux:tooltip content="{{ __('View') }}" position="top">
                                    <button class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100 hover:text-wine-800">
                                        <flux:icon.eye variant="mini" class="size-4" />
                                    </button>
                                </flux:tooltip>
                                <flux:tooltip content="{{ __('Delete') }}" position="top">
                                    <button class="flex h-8 w-8 items-center justify-center rounded-full text-zinc-400 transition hover:bg-red-50 hover:text-red-600">
                                        <flux:icon.trash variant="mini" class="size-4" />
                                    </button>
                                </flux:tooltip>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between">
        <flux:text variant="subtle" class="text-sm">{{ __('Showing :from to :to of :total contacts', ['from' => 1, 'to' => 10, 'total' => 1248]) }}</flux:text>

        <div class="flex items-center gap-1">
            <flux:button variant="ghost" size="sm" icon="chevron-left" disabled />
            <flux:button variant="primary" size="sm">1</flux:button>
            <flux:button variant="ghost" size="sm">2</flux:button>
            <flux:button variant="ghost" size="sm">3</flux:button>
            <flux:text variant="subtle" class="px-1">...</flux:text>
            <flux:button variant="ghost" size="sm">125</flux:button>
            <flux:button variant="ghost" size="sm" icon="chevron-right" />
        </div>
    </div>

    {{-- Bulk actions floating bar --}}
    <x-bulk-actions>
        <flux:button variant="ghost" size="sm" icon="trash">{{ __('Delete') }}</flux:button>
        <flux:button variant="ghost" size="sm" icon="arrow-down-tray">{{ __('Export') }}</flux:button>
    </x-bulk-actions>
</div>
