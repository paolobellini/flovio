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
                    <flux:button variant="ghost" icon="plus" size="sm">{{ __('Add') }}</flux:button>
                </div>
                <div class="divide-y divide-zinc-100">
                    @foreach ([
                        ['name' => 'Newsletter', 'count' => '1,248'],
                        ['name' => 'Product Updates', 'count' => '892'],
                        ['name' => 'VIP Customers', 'count' => '156'],
                    ] as $list)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-zinc-100">
                                    <flux:icon.users variant="mini" class="size-4 text-zinc-500" />
                                </div>
                                <flux:text class="font-medium">{{ $list['name'] }}</flux:text>
                            </div>
                            <flux:badge size="sm" color="zinc" inset="top bottom">{{ $list['count'] }}</flux:badge>
                        </div>
                    @endforeach
                </div>
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
</div>
