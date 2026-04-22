<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Dashboard') }}</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ __('Welcome back to your Flovio dashboard.') }}</p>
        </div>

        <div class="grid auto-rows-min gap-5 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-zinc-200 bg-white">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-zinc-200" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-zinc-200 bg-white">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-zinc-200" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-zinc-200 bg-white">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-zinc-200" />
            </div>
        </div>
        <div class="relative h-full min-h-48 flex-1 overflow-hidden rounded-xl border border-zinc-200 bg-white">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-zinc-200" />
        </div>
    </div>
</x-layouts::app>
