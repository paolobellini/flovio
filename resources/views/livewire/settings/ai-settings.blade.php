<section class="w-full">
    @include('partials.settings-heading', ['currentTab' => 'ai'])

    <flux:heading class="sr-only">{{ __('AI settings') }}</flux:heading>

    <x-settings.layout :heading="__('AI')" :subheading="__('Configure AI models and API keys for each feature')">
        <form wire:submit="updateAiSettings" class="my-6 w-full space-y-6">
            <flux:separator text="{{ __('Models') }}" />

            <flux:input wire:model="chat_model" :label="__('Chat model')" type="text" required />

            <flux:input wire:model="image_model" :label="__('Image model')" type="text" required />

            <flux:input wire:model="content_model" :label="__('Content model')" type="text" required />

            <flux:separator text="{{ __('API keys') }}" />

            <flux:input wire:model="openai_api_key" :label="__('OpenAI API key')" type="password" viewable />

            <flux:input wire:model="anthropic_api_key" :label="__('Anthropic API key')" type="password" viewable />

            <flux:input wire:model="google_api_key" :label="__('Google API key')" type="password" viewable />

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>
