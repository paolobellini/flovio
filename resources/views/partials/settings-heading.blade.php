<div class="relative mb-6 w-full">
    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Settings') }}</h1>
    <p class="mt-1 text-sm text-zinc-500">{{ __('Manage your profile and account settings') }}</p>

    <flux:navbar class="mt-4">
        <flux:navbar.item icon="user" :href="route('profile.edit')" :current="$currentTab === 'profile'" wire:navigate>
            {{ __('Profile') }}
        </flux:navbar.item>
        <flux:navbar.item icon="envelope" :href="route('mailgun.edit')" :current="$currentTab === 'mailgun'" wire:navigate>
            {{ __('Mailgun') }}
        </flux:navbar.item>
        <flux:navbar.item icon="lock-closed" :href="route('security.edit')" :current="$currentTab === 'security'" wire:navigate>
            {{ __('Security') }}
        </flux:navbar.item>
    </flux:navbar>
</div>
