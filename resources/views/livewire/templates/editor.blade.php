<div class="flex h-full w-full flex-1 flex-col gap-6" x-data="{ view: 'desktop' }">
    {{-- Top bar --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <flux:button variant="ghost" icon="arrow-left" :href="route('templates.index')" wire:navigate>
                {{ __('Templates') }}
            </flux:button>
            <flux:separator vertical class="h-6" />
            <div>
                <h1 class="text-lg font-semibold text-zinc-900">{{ $this->isEditing ? $template->name : __('New template') }}</h1>
                @if ($this->isEditing)
                    <flux:text variant="subtle" class="text-xs">{{ __('Last saved :time ago', ['time' => $template->updated_at->diffForHumans(short: true)]) }}</flux:text>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- View toggle --}}
            <div class="flex items-center rounded-lg border border-zinc-200 bg-zinc-50 p-0.5">
                <button
                    x-on:click="view = 'desktop'"
                    x-bind:class="view === 'desktop' ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-400 hover:text-zinc-600'"
                    class="flex h-7 w-7 items-center justify-center rounded-md transition"
                >
                    <flux:icon.computer-desktop variant="mini" class="size-4" />
                </button>
                <button
                    x-on:click="view = 'mobile'"
                    x-bind:class="view === 'mobile' ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-400 hover:text-zinc-600'"
                    class="flex h-7 w-7 items-center justify-center rounded-md transition"
                >
                    <flux:icon.device-phone-mobile variant="mini" class="size-4" />
                </button>
            </div>

            <flux:separator vertical class="h-6" />
            @if ($this->isEditing)
                <flux:button variant="ghost" icon="trash" size="sm" class="text-red-600 hover:text-red-700" wire:click="confirmDelete">{{ __('Delete') }}</flux:button>
            @endif
            <flux:button variant="primary" icon="check" size="sm" wire:click="save">{{ __('Save') }}</flux:button>
        </div>
    </div>

    {{-- Editor layout --}}
    <div class="flex flex-1 gap-6 overflow-hidden">
        {{-- Left panel --}}
        <div class="w-80 shrink-0 space-y-5 overflow-y-auto">
            {{-- AI Generator (first, primary focus) --}}
            <div class="rounded-2xl border border-wine-200/60 bg-gradient-to-br from-wine-50 to-white p-5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-wine-100 text-wine-700">
                        <flux:icon.sparkles variant="mini" class="size-5" />
                    </div>
                    <div>
                        <flux:heading size="sm" class="text-wine-900">{{ __('AI Design') }}</flux:heading>
                        <p class="text-xs text-wine-600/70">{{ __('Powered by AI') }}</p>
                    </div>
                </div>

                {{-- Quick prompts --}}
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach ([
                        __('Newsletter'),
                        __('Promo'),
                        __('Welcome'),
                        __('Event invite'),
                        __('Re-engagement'),
                    ] as $prompt)
                        <button class="rounded-full border border-wine-200/80 bg-white px-2.5 py-1 text-xs text-wine-700 transition hover:bg-wine-100">
                            {{ $prompt }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-4 space-y-3">
                    <flux:textarea wire:model="prompt" :placeholder="__('Describe your template...')" rows="4" class="text-sm" />

                    <div class="flex gap-2">
                        <flux:button variant="primary" icon="sparkles" class="flex-1">{{ __('Generate') }}</flux:button>
                        <flux:button variant="ghost" icon="arrow-path" class="shrink-0">{{ __('Refine') }}</flux:button>
                    </div>
                </div>
            </div>

            {{-- Style preferences (AI context) --}}
            <div class="rounded-2xl border border-zinc-200/80 bg-white p-5 shadow-sm">
                <flux:heading size="sm">{{ __('Style') }}</flux:heading>
                <p class="mt-1 text-xs text-zinc-400">{{ __('Guides the AI output') }}</p>
                <div class="mt-4 space-y-4">
                    <div>
                        <flux:label>{{ __('Primary color') }}</flux:label>
                        <div class="mt-2 flex items-center gap-2">
                            <div class="h-8 w-8 rounded-lg ring-2 ring-zinc-200/50" style="background-color: {{ $primary_color }}"></div>
                            <flux:input wire:model.blur="primary_color" class="flex-1 font-mono text-sm" />
                        </div>
                    </div>
                    <flux:select wire:model="layout" :label="__('Layout')">
                        <flux:select.option value="single">{{ __('Single column') }}</flux:select.option>
                        <flux:select.option value="two-column">{{ __('Two columns') }}</flux:select.option>
                        <flux:select.option value="hero">{{ __('Hero image') }}</flux:select.option>
                    </flux:select>
                    <flux:select wire:model="tone" :label="__('Tone')">
                        <flux:select.option value="professional">{{ __('Professional') }}</flux:select.option>
                        <flux:select.option value="casual">{{ __('Casual') }}</flux:select.option>
                        <flux:select.option value="elegant">{{ __('Elegant') }}</flux:select.option>
                    </flux:select>
                </div>
            </div>

            {{-- Template details --}}
            <div class="rounded-2xl border border-zinc-200/80 bg-white p-5 shadow-sm">
                <flux:heading size="sm">{{ __('Template details') }}</flux:heading>
                <div class="mt-4 space-y-4">
                    <flux:input wire:model="name" :label="__('Name')" />
                    <flux:textarea wire:model="description" :label="__('Description')" :placeholder="__('What is this template for?')" rows="2" class="text-sm" />
                </div>
            </div>
        </div>

        {{-- Right: Live preview --}}
        <div class="flex-1 overflow-y-auto rounded-2xl border border-zinc-200/80 bg-zinc-100 shadow-inner">
            <div class="flex h-full items-start justify-center p-8" x-bind:class="view === 'mobile' && 'items-center'">
                {{-- Desktop view --}}
                <div x-show="view === 'desktop'" x-transition class="w-full max-w-[600px]">
                    <div class="rounded-xl bg-white shadow-lg shadow-zinc-200/50">
                        {{-- Email header --}}
                        <div class="rounded-t-xl bg-gradient-to-r from-wine-700 to-wine-600 px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                    <flux:icon.paper-airplane variant="mini" class="size-5 text-white" />
                                </div>
                                <span class="text-lg font-semibold text-white">Flovio</span>
                            </div>
                        </div>

                        {{-- Email body --}}
                        <div class="space-y-6 px-8 py-8">
                            <div>
                                <h2 class="text-2xl font-bold text-zinc-900">Newsletter settimanale</h2>
                                <p class="mt-2 leading-relaxed text-zinc-600">
                                    Ciao <span class="font-medium text-wine-700">%recipient.name%</span>,
                                    ecco le novità della settimana dalla nostra cantina. Scopri i nuovi vini, eventi esclusivi e offerte speciali pensate per te.
                                </p>
                            </div>

                            <div class="flex aspect-video items-center justify-center rounded-lg bg-gradient-to-br from-zinc-100 to-zinc-200">
                                <div class="text-center">
                                    <flux:icon.photo variant="outline" class="mx-auto size-10 text-zinc-300" />
                                    <p class="mt-2 text-sm text-zinc-400">600 × 300</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900">In evidenza questa settimana</h3>
                                <p class="mt-2 leading-relaxed text-zinc-600">
                                    Il nostro Barolo Riserva 2018 ha ricevuto 95 punti dalla critica. Un vino elegante con note di ciliegia, tabacco e spezie dolci. Disponibile in edizione limitata.
                                </p>
                            </div>

                            <div class="text-center">
                                <a href="#" class="inline-block rounded-full bg-wine-700 px-8 py-3 font-semibold text-white transition hover:bg-wine-800">
                                    Scopri la collezione
                                </a>
                            </div>

                            <hr class="border-zinc-200" />

                            <p class="text-center text-xs leading-relaxed text-zinc-400">
                                Ricevi questa email perché sei iscritto alla nostra newsletter.
                                <a href="#" class="text-wine-600 underline">Disiscriviti</a>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Mobile view --}}
                <div x-show="view === 'mobile'" x-transition x-cloak>
                    <div class="overflow-hidden rounded-[2.5rem] border-4 border-zinc-800 bg-zinc-800 shadow-2xl" style="width: 375px;">
                        {{-- Notch --}}
                        <div class="flex justify-center bg-zinc-800 pb-2 pt-3">
                            <div class="h-5 w-28 rounded-full bg-zinc-900"></div>
                        </div>

                        {{-- Screen --}}
                        <div class="bg-white">
                            <div class="flex items-center justify-between bg-zinc-50 px-5 py-2">
                                <span class="text-xs font-medium text-zinc-500">9:41</span>
                                <div class="flex items-center gap-1">
                                    <div class="h-2.5 w-4 rounded-sm bg-zinc-400"></div>
                                </div>
                            </div>

                            <div class="max-h-[500px] overflow-y-auto">
                                <div class="bg-gradient-to-r from-wine-700 to-wine-600 px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                                            <flux:icon.paper-airplane variant="mini" class="size-4 text-white" />
                                        </div>
                                        <span class="font-semibold text-white">Flovio</span>
                                    </div>
                                </div>

                                <div class="space-y-4 px-5 py-5">
                                    <div>
                                        <h2 class="text-lg font-bold text-zinc-900">Newsletter settimanale</h2>
                                        <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">
                                            Ciao <span class="font-medium text-wine-700">%recipient.name%</span>,
                                            ecco le novità della settimana dalla nostra cantina.
                                        </p>
                                    </div>

                                    <div class="aspect-video rounded-lg bg-gradient-to-br from-zinc-100 to-zinc-200"></div>

                                    <div>
                                        <h3 class="font-semibold text-zinc-900">In evidenza</h3>
                                        <p class="mt-1 text-sm leading-relaxed text-zinc-600">
                                            Il nostro Barolo Riserva 2018 ha ricevuto 95 punti dalla critica.
                                        </p>
                                    </div>

                                    <div class="text-center">
                                        <a href="#" class="inline-block w-full rounded-full bg-wine-700 px-6 py-2.5 text-sm font-semibold text-white">
                                            Scopri la collezione
                                        </a>
                                    </div>

                                    <hr class="border-zinc-200" />

                                    <p class="text-center text-xs text-zinc-400">
                                        <a href="#" class="text-wine-600 underline">Disiscriviti</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Home indicator --}}
                        <div class="flex justify-center bg-zinc-800 pb-2 pt-3">
                            <div class="h-1 w-28 rounded-full bg-zinc-600"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete confirmation modal --}}
    @if ($this->isEditing)
        <x-confirm-delete
            name="confirm-delete-template"
            :heading="__('Delete template')"
            :description="__('Are you sure you want to delete this template? This action cannot be undone.')"
        />
    @endif
</div>
