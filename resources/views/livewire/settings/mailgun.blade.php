<section class="w-full">
    @include('partials.settings-heading', ['currentTab' => 'mailgun'])

    <flux:heading class="sr-only">{{ __('Mailgun settings') }}</flux:heading>

    <x-settings.layout :heading="__('Mailgun')" :subheading="__('Manage your Mailgun API settings and sender configuration')">
        <form wire:submit="updateMailgunSettings" class="my-6 w-full space-y-6">
            <flux:input wire:model="mailgun_api_key" :label="__('API key')" type="password" required viewable />

            <flux:input wire:model="mailgun_domain" :label="__('Sending domain')" type="text" required />

            <flux:input wire:model="sender_name" :label="__('Sender name')" type="text" required />

            <flux:input wire:model="sender_email" :label="__('Sender email')" type="email" required />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>
