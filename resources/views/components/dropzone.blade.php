@props([
    'model' => '',
    'accept' => '.csv,.txt',
    'hint' => '',
    'filename' => null,
    'filesize' => null,
])

<div
    x-data="{ dragging: false }"
    x-on:dragover.prevent="dragging = true"
    x-on:dragleave.prevent="dragging = false"
    x-on:drop.prevent="dragging = false; $wire.upload('{{ $model }}', $event.dataTransfer.files[0])"
>
    <label
        for="dropzone-{{ $model }}"
        class="flex cursor-pointer flex-col items-center gap-3 rounded-xl border-2 border-dashed p-8 text-center transition-colors"
        :class="dragging ? 'border-wine-400 bg-wine-50/50' : 'border-zinc-300 bg-zinc-50/50 hover:border-zinc-400 hover:bg-zinc-100/50'"
    >
        @if ($filename)
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                <flux:icon.document-check class="size-6 text-green-600" />
            </div>
            <div>
                <p class="text-sm font-medium text-zinc-900">{{ $filename }}</p>
                @if ($filesize)
                    <p class="mt-1 text-xs text-zinc-500">{{ $filesize }}</p>
                @endif
            </div>
            <flux:button variant="ghost" size="sm" wire:click="$set('{{ $model }}', null)">
                {{ __('Choose a different file') }}
            </flux:button>
        @else
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100" :class="dragging && '!bg-wine-100'">
                <flux:icon.arrow-up-tray class="size-6 text-zinc-400" ::class="dragging && '!text-wine-600'" />
            </div>
            <div>
                <p class="text-sm font-medium text-zinc-700">
                    <span class="text-wine-700 underline decoration-wine-300 underline-offset-2">{{ __('Click to upload') }}</span>
                    {{ __('or drag and drop') }}
                </p>
                @if ($hint)
                    <p class="mt-1 text-xs text-zinc-500">{{ $hint }}</p>
                @endif
            </div>
        @endif
    </label>
    <input id="dropzone-{{ $model }}" type="file" wire:model="{{ $model }}" accept="{{ $accept }}" class="sr-only" />
    @error($model) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

    {{-- Loading indicator --}}
    <div wire:loading wire:target="{{ $model }}" class="mt-2 flex items-center justify-center gap-2 text-sm text-zinc-500">
        <flux:icon.arrow-path class="size-4 animate-spin" />
        {{ __('Uploading...') }}
    </div>
</div>
