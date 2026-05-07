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

        {{-- Step 2: Map columns --}}
        @if ($step === 2)
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Map columns') }}</flux:heading>
                    <flux:subheading>{{ __(':count rows found. Pick which column holds the name and which holds the email.', ['count' => $totalRows]) }}</flux:subheading>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:select wire:model.live="nameColumn" :label="__('Name column')" :placeholder="__('Select a column')">
                        @foreach ($headers as $header)
                            <flux:select.option value="{{ $header }}">{{ $header }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="emailColumn" :label="__('Email column')" :placeholder="__('Select a column')">
                        @foreach ($headers as $header)
                            <flux:select.option value="{{ $header }}">{{ $header }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                @if ($nameColumn && $emailColumn && $previewRows !== [])
                    @php
                        $nameIndex = array_search($nameColumn, $headers, true);
                        $emailIndex = array_search($emailColumn, $headers, true);
                    @endphp

                    <div class="rounded-lg border border-zinc-200 bg-zinc-50/50">
                        <div class="grid grid-cols-2 border-b border-zinc-200 px-4 py-2 text-xs font-semibold tracking-wide text-zinc-500 uppercase">
                            <span>{{ __('Name') }}</span>
                            <span>{{ __('Email') }}</span>
                        </div>
                        @foreach (array_slice($previewRows, 0, 3) as $row)
                            <div class="grid grid-cols-2 px-4 py-2 text-sm text-zinc-700 not-last:border-b not-last:border-zinc-200">
                                <span class="truncate">{{ $nameIndex !== false ? ($row[$nameIndex] ?? '—') : '—' }}</span>
                                <span class="truncate">{{ $emailIndex !== false ? ($row[$emailIndex] ?? '—') : '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

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

        {{-- Step 3: Started --}}
        @if ($step === 3)
            <div class="space-y-6">
                <div class="flex flex-col items-center space-y-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-sky-100">
                        <flux:icon.paper-airplane class="size-6 text-sky-600" />
                    </div>
                    <div class="space-y-2 text-center">
                        <flux:heading size="lg">{{ __('Import started') }}</flux:heading>
                        <flux:subheading>{{ __('You can close this window. We will notify you when the import is finished.') }}</flux:subheading>
                    </div>
                </div>

                <div class="flex justify-center pt-2">
                    <flux:modal.close>
                        <flux:button variant="primary">{{ __('Close') }}</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
