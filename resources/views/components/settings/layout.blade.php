<div class="w-full max-w-lg">
    <flux:heading>{{ $heading ?? '' }}</flux:heading>
    <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

    <div class="mt-5">
        {{ $slot }}
    </div>
</div>
