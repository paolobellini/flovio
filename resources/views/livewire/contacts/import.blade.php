<div>
    <flux:modal name="import-contacts" class="max-w-2xl md:min-w-2xl" @close="$wire.resetImport()">
        {{-- Step 1: Upload --}}
        @if ($step === 1)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Import contacts') }}</flux:heading>
                    <flux:subheading>{{ __('Upload a CSV file to import contacts in bulk.') }}</flux:subheading>
                </div>

                <x-dropzone
                    model="file"
                    accept=".csv,.txt"
                    :hint="__('CSV file up to 5 MB')"
                    :filename="$file?->getClientOriginalName()"
                    :filesize="$file ? number_format($file->getSize() / 1024, 1) . ' KB' : null"
                />

                <div class="flex gap-3 pt-2">
                    <flux:modal.close>
                        <flux:button variant="filled" class="w-full">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" class="w-full" wire:click="preview" :disabled="! $file">
                        {{ __('Continue') }}
                    </flux:button>
                </div>
            </div>
        @endif

        {{-- Step 2: Preview & Map --}}
        @if ($step === 2)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Map columns') }}</flux:heading>
                    <flux:subheading>{{ __(':count rows found. Assign each column to a contact field.', ['count' => $totalRows]) }}</flux:subheading>
                </div>

                {{-- Visual column cards --}}
                <div class="grid gap-3" style="grid-template-columns: repeat({{ min(count($headers), 4) }}, minmax(0, 1fr));">
                    @foreach ($headers as $headerIndex => $header)
                        @php
                            $mappedAs = match($header) {
                                $nameColumn => 'name',
                                $emailColumn => 'email',
                                default => null,
                            };
                            $borderColor = match($mappedAs) {
                                'name' => 'border-sky-300 bg-sky-50/50',
                                'email' => 'border-violet-300 bg-violet-50/50',
                                default => 'border-zinc-200 bg-white',
                            };
                        @endphp
                        <div class="rounded-lg border-2 {{ $borderColor }} p-3 transition-colors">
                            {{-- Column header --}}
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-xs font-semibold tracking-wide text-zinc-500 uppercase">{{ $header }}</span>
                                @if ($mappedAs === 'name')
                                    <flux:badge size="sm" color="sky">{{ __('Name') }}</flux:badge>
                                @elseif ($mappedAs === 'email')
                                    <flux:badge size="sm" color="violet">{{ __('Email') }}</flux:badge>
                                @endif
                            </div>

                            {{-- Sample values --}}
                            <div class="mb-3 space-y-1">
                                @foreach (array_slice($previewRows, 0, 3) as $row)
                                    <p class="truncate text-sm text-zinc-600">{{ $row[$headerIndex] ?? '—' }}</p>
                                @endforeach
                            </div>

                            {{-- Mapping toggle buttons --}}
                            <div class="flex gap-1">
                                <button
                                    type="button"
                                    wire:click="$set('nameColumn', '{{ $header === $nameColumn ? '' : $header }}')"
                                    class="flex-1 rounded px-2 py-1 text-xs font-medium transition-colors {{ $mappedAs === 'name' ? 'bg-sky-200 text-sky-800' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200' }}"
                                >
                                    {{ __('Name') }}
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('emailColumn', '{{ $header === $emailColumn ? '' : $header }}')"
                                    class="flex-1 rounded px-2 py-1 text-xs font-medium transition-colors {{ $mappedAs === 'email' ? 'bg-violet-200 text-violet-800' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200' }}"
                                >
                                    {{ __('Email') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex gap-3 pt-2">
                    <flux:button variant="filled" class="w-full" wire:click="$set('step', 1)">
                        {{ __('Back') }}
                    </flux:button>
                    <flux:button variant="primary" class="w-full" wire:click="import" :disabled="! $nameColumn || ! $emailColumn">
                        {{ __('Import :count contacts', ['count' => $totalRows]) }}
                    </flux:button>
                </div>
            </div>
        @endif

        {{-- Step 3: Results --}}
        @if ($step === 3)
            <div class="space-y-6">
                <div class="flex flex-col items-center space-y-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                        <flux:icon.check-circle class="size-6 text-green-600" />
                    </div>
                    <div class="space-y-2 text-center">
                        <flux:heading size="lg">{{ __('Import complete') }}</flux:heading>
                        <flux:subheading>{{ __('Your contacts have been processed.') }}</flux:subheading>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-center">
                        <div class="text-lg font-semibold text-green-700">{{ $createdCount }}</div>
                        <flux:text variant="subtle" class="text-xs">{{ __('Created') }}</flux:text>
                    </div>
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 text-center">
                        <div class="text-lg font-semibold text-zinc-600">{{ $skippedCount }}</div>
                        <flux:text variant="subtle" class="text-xs">{{ __('Skipped') }}</flux:text>
                    </div>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-center">
                        <div class="text-lg font-semibold text-red-700">{{ $failedCount }}</div>
                        <flux:text variant="subtle" class="text-xs">{{ __('Failed') }}</flux:text>
                    </div>
                </div>

                <div class="flex pt-2">
                    <flux:modal.close>
                        <flux:button variant="primary" class="w-full">{{ __('Done') }}</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
