@props([
    'name' => 'confirm-delete',
    'heading' => __('Delete'),
    'description' => __('Are you sure? This action cannot be undone.'),
    'action' => 'delete',
])

<flux:modal :name="$name" class="max-w-sm">
    <div class="space-y-6">
        <div class="flex flex-col items-center space-y-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                <flux:icon.trash class="size-6 text-red-600" />
            </div>
            <div class="space-y-2 text-center">
                <flux:heading size="lg">{{ $heading }}</flux:heading>
                <flux:text variant="subtle">{{ $description }}</flux:text>
            </div>
        </div>

        <div class="flex gap-3">
            <flux:modal.close>
                <flux:button variant="filled" class="w-full">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>
            <flux:button variant="danger" wire:click="{{ $action }}" class="w-full">{{ __('Delete') }}</flux:button>
        </div>
    </div>
</flux:modal>
