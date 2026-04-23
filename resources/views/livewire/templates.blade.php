<div class="flex h-full w-full flex-1 flex-col gap-8">
    <x-page-header :heading="__('Templates')" :description="__('Design reusable email templates for your campaigns.')">
        <flux:button variant="primary" icon="plus" :href="route('templates.create')" wire:navigate>{{ __('Create template') }}</flux:button>
    </x-page-header>

    {{-- Search --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="flex-1">
            <flux:input icon="magnifying-glass" :placeholder="__('Search templates...')" class="max-w-sm" />
        </div>
    </div>

    {{-- Templates grid --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
            ['name' => 'Newsletter Classica', 'subject' => 'Le novità della settimana', 'category' => 'newsletter', 'ai' => false, 'used' => 8, 'updated' => '22 apr 2026, 10:30'],
            ['name' => 'Promo Stagionale', 'subject' => 'Offerte speciali per te', 'category' => 'promo', 'ai' => false, 'used' => 4, 'updated' => '20 apr 2026, 14:15'],
            ['name' => 'Welcome Email', 'subject' => 'Benvenuto in Flovio!', 'category' => 'transactional', 'ai' => true, 'used' => 12, 'updated' => '18 apr 2026, 09:00'],
            ['name' => 'Evento Degustazione', 'subject' => 'Sei invitato alla nostra degustazione', 'category' => 'event', 'ai' => false, 'used' => 2, 'updated' => '15 apr 2026, 16:45'],
            ['name' => 'Re-engagement', 'subject' => 'Ci manchi! Torna a trovarci', 'category' => 'automation', 'ai' => true, 'used' => 0, 'updated' => '10 apr 2026, 11:20'],
        ] as $template)
            <div class="group cursor-pointer rounded-2xl border border-zinc-200/80 bg-white transition-all duration-200 hover:border-wine-200 hover:shadow-lg">
                {{-- Preview area --}}
                <div class="relative aspect-[4/3] overflow-hidden rounded-t-2xl bg-gradient-to-br from-zinc-50 to-zinc-100">
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                        <div class="w-full max-w-[180px] space-y-2.5">
                            <div class="mx-auto h-6 w-20 rounded bg-wine-200/60"></div>
                            <div class="space-y-1.5">
                                <div class="h-2 w-full rounded-full bg-zinc-200/80"></div>
                                <div class="h-2 w-4/5 rounded-full bg-zinc-200/80"></div>
                                <div class="h-2 w-3/5 rounded-full bg-zinc-200/80"></div>
                            </div>
                            <div class="h-16 w-full rounded-lg bg-zinc-200/50"></div>
                            <div class="space-y-1.5">
                                <div class="h-2 w-full rounded-full bg-zinc-200/80"></div>
                                <div class="h-2 w-2/3 rounded-full bg-zinc-200/80"></div>
                            </div>
                            <div class="mx-auto h-5 w-16 rounded-full bg-wine-300/50"></div>
                        </div>
                    </div>

                    {{-- Actions overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center gap-2 bg-zinc-900/0 transition-all duration-200 group-hover:bg-zinc-900/40">
                        <div class="flex items-center gap-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            <flux:button variant="primary" size="sm" icon="pencil-square" :href="route('templates.edit', 1)" wire:navigate>{{ __('Edit') }}</flux:button>
                            <flux:button variant="filled" size="sm" icon="eye">{{ __('Preview') }}</flux:button>
                        </div>
                    </div>

                    {{-- AI badge --}}
                    @if ($template['ai'])
                        <div class="absolute left-3 top-3">
                            <flux:badge color="purple" size="sm">
                                <flux:icon.sparkles variant="mini" class="mr-1 size-3" /> {{ __('AI') }}
                            </flux:badge>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="font-semibold text-zinc-900">{{ $template['name'] }}</h3>
                    <p class="mt-0.5 text-sm text-zinc-400 line-clamp-1">{{ $template['subject'] }}</p>

                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                                <flux:icon.paper-airplane variant="mini" class="size-3.5" />
                                <span class="font-medium tabular-nums text-zinc-600">{{ $template['used'] }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                                <flux:icon.clock variant="mini" class="size-3.5" />
                                <span>{{ $template['updated'] }}</span>
                            </div>
                        </div>
                        <flux:tooltip content="{{ __('Delete') }}" position="top">
                            <button class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-red-50 hover:text-red-600">
                                <flux:icon.trash variant="mini" class="size-3.5" />
                            </button>
                        </flux:tooltip>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
