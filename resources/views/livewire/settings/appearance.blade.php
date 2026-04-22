<section class="w-full">
    @include('partials.settings-heading', ['currentTab' => 'appearance'])

    <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
