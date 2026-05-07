<div class="flex h-full w-full flex-1 flex-col gap-8">
    <x-page-header :heading="__('Templates')" :description="__('Design reusable email templates for your campaigns.')">
        <flux:button variant="primary" icon="plus" :href="route('templates.create')" wire:navigate>{{ __('Create template') }}</flux:button>
    </x-page-header>

    {{-- Search --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="flex-1">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" :placeholder="__('Search templates...')" class="max-w-sm" />
        </div>
    </div>

    {{-- Templates grid --}}
    @if ($this->templates->isNotEmpty())
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->templates as $template)
                <div class="group cursor-pointer rounded-2xl border border-zinc-200/80 bg-white transition-all duration-200 hover:border-wine-200 hover:shadow-lg">
                    {{-- Preview area --}}
                    <div class="relative aspect-[4/3] overflow-hidden rounded-t-2xl bg-gradient-to-br from-zinc-50 to-zinc-100">
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                            <div class="w-full max-w-[180px] space-y-2.5">
                                <div class="mx-auto h-6 w-20 rounded" style="background-color: {{ $template->primary_color }}40"></div>
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
                                <div class="mx-auto h-5 w-16 rounded-full" style="background-color: {{ $template->primary_color }}60"></div>
                            </div>
                        </div>

                        {{-- Actions overlay --}}
                        <div class="absolute inset-0 flex items-center justify-center gap-2 bg-zinc-900/0 transition-all duration-200 group-hover:bg-zinc-900/40">
                            <div class="flex items-center gap-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                <flux:button variant="primary" size="sm" icon="pencil-square" :href="route('templates.edit', $template)" wire:navigate>{{ __('Edit') }}</flux:button>
                            </div>
                        </div>

                        {{-- Layout badge --}}
                        <div class="absolute left-3 top-3">
                            <flux:badge color="zinc" size="sm">{{ $template->layout->label() }}</flux:badge>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <h3 class="font-semibold text-zinc-900">{{ $template->name }}</h3>
                        @if ($template->description)
                            <p class="mt-0.5 text-sm text-zinc-400 line-clamp-1">{{ $template->description }}</p>
                        @endif

                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                                    <flux:icon.swatch variant="mini" class="size-3.5" />
                                    <span class="text-zinc-500">{{ $template->tone->label() }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                                    <flux:icon.clock variant="mini" class="size-3.5" />
                                    <span>{{ $template->updated_at->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            <flux:tooltip content="{{ __('Delete') }}" position="top">
                                <button wire:click="confirmDelete({{ $template->id }})" class="flex h-7 w-7 items-center justify-center rounded-full text-zinc-300 transition hover:bg-red-50 hover:text-red-600">
                                    <flux:icon.trash variant="mini" class="size-3.5" />
                                </button>
                            </flux:tooltip>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <x-empty-state
            icon="document-text"
            :heading="__('No templates yet.')"
            :description="__('Create your first template to start designing emails.')"
        >
            <flux:button variant="primary" icon="plus" :href="route('templates.create')" wire:navigate>{{ __('Create template') }}</flux:button>
        </x-empty-state>
    @endif

    {{-- Delete confirmation modal --}}
    <x-confirm-delete
        name="confirm-delete-template"
        :heading="__('Delete template')"
        :description="__('Are you sure you want to delete this template? This action cannot be undone.')"
    />
</div>
