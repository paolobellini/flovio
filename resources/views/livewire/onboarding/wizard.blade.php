<div class="flex flex-col gap-8">
    {{-- Step indicator --}}
    <div class="flex items-center justify-center gap-2">
        @for ($i = 1; $i <= $totalSteps; $i++)
            <button
                wire:click="goToStep({{ $i }})"
                @class([
                    'h-2 rounded-full transition-all',
                    'w-8 bg-wine-800' => $i === $currentStep,
                    'w-2 bg-wine-300' => $i < $currentStep,
                    'w-2 bg-zinc-200' => $i > $currentStep,
                    'cursor-pointer' => $i < $currentStep,
                    'cursor-default' => $i >= $currentStep,
                ])
                @if($i >= $currentStep) disabled @endif
            ></button>
        @endfor
    </div>

    {{-- Step 1: Profile --}}
    @if ($currentStep === 1)
        <div class="flex flex-col gap-1 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Tell us about yourself') }}</h1>
            <p class="text-sm text-zinc-500">{{ __('This information helps us personalize your experience.') }}</p>
        </div>

        <div class="flex flex-col gap-5">
            <flux:input
                wire:model="name"
                :label="__('Name')"
                type="text"
                required
                autofocus
                autocomplete="name"
            />

            <flux:input
                wire:model="company_name"
                :label="__('Company name') . ' (' . __('optional') . ')'"
                type="text"
                :placeholder="__('Your agency or business name')"
            />

            <div
                x-data="{
                    search: $wire.timezone,
                    open: false,
                    timezones: @js(timezone_identifiers_list()),
                    get filtered() {
                        if (!this.search) return this.timezones.slice(0, 20);
                        const q = this.search.toLowerCase();
                        return this.timezones.filter(tz => tz.toLowerCase().includes(q)).slice(0, 20);
                    },
                    select(tz) {
                        this.search = tz;
                        $wire.timezone = tz;
                        this.open = false;
                    },
                }"
                x-init="
                    if (!$wire.timezone || $wire.timezone === '{{ config('app.timezone', 'UTC') }}') {
                        const browserTz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                        if (timezones.includes(browserTz)) {
                            select(browserTz);
                        }
                    }
                "
                @click.outside="open = false"
                class="relative"
            >
                <flux:input
                    x-model="search"
                    @focus="open = true"
                    @input="open = true"
                    :label="__('Timezone')"
                    type="text"
                    required
                    placeholder="Europe/Rome"
                    autocomplete="off"
                />

                <div
                    x-show="open && filtered.length > 0"
                    x-transition.opacity
                    class="absolute z-50 mt-1 max-h-48 w-full overflow-y-auto rounded-lg border border-zinc-200 bg-white shadow-lg"
                >
                    <template x-for="tz in filtered" :key="tz">
                        <button
                            type="button"
                            @click="select(tz)"
                            class="w-full px-3 py-2 text-left text-sm text-zinc-700 hover:bg-zinc-50"
                            x-text="tz"
                        ></button>
                    </template>
                </div>
            </div>

            <flux:button variant="primary" wire:click="nextStep" class="w-full">
                {{ __('Continue') }}
            </flux:button>
        </div>
    @endif

    {{-- Step 2: Mailgun --}}
    @if ($currentStep === 2)
        <div class="flex flex-col gap-1 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Connect Mailgun') }}</h1>
            <p class="text-sm text-zinc-500">{{ __('Configure your Mailgun account to start sending emails.') }}</p>
        </div>

        <div class="flex flex-col gap-5">
            <flux:input
                wire:model="mailgun_api_key"
                :label="__('API key')"
                type="password"
                required
                autofocus
                placeholder="key-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
                viewable
            />

            <flux:input
                wire:model="sender_name"
                :label="__('Sender name')"
                type="text"
                required
                :placeholder="__('Your Company')"
            />

            <flux:input
                wire:model="sender_email"
                :label="__('Sender email')"
                type="email"
                required
                placeholder="hello@example.com"
            />

            <flux:input
                wire:model="mailgun_domain"
                :label="__('Sending domain')"
                type="text"
                required
                placeholder="mg.example.com"
            />

            <div class="flex gap-3">
                <flux:button variant="outline" wire:click="previousStep" class="w-full">
                    {{ __('Back') }}
                </flux:button>
                <flux:button variant="primary" wire:click="nextStep" class="w-full">
                    {{ __('Continue') }}
                </flux:button>
            </div>
        </div>
    @endif

    {{-- Step 3: Summary --}}
    @if ($currentStep === 3)
        <div class="flex flex-col gap-1 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('You\'re all set') }}</h1>
            <p class="text-sm text-zinc-500">{{ __('Review your settings before getting started.') }}</p>
        </div>

        <div class="flex flex-col gap-5">
            {{-- Profile summary --}}
            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Profile') }}</span>
                    <button wire:click="goToStep(1)" class="text-xs font-medium text-wine-700 hover:text-wine-900">{{ __('Edit') }}</button>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">{{ __('Name') }}</dt>
                        <dd class="font-medium text-zinc-900">{{ $name }}</dd>
                    </div>
                    @if ($company_name)
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">{{ __('Company') }}</dt>
                            <dd class="font-medium text-zinc-900">{{ $company_name }}</dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">{{ __('Timezone') }}</dt>
                        <dd class="font-medium text-zinc-900">{{ $timezone }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Mailgun summary --}}
            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-6">
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Mailgun') }}</span>
                    <button wire:click="goToStep(2)" class="text-xs font-medium text-wine-700 hover:text-wine-900">{{ __('Edit') }}</button>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">{{ __('Domain') }}</dt>
                        <dd class="font-medium text-zinc-900">{{ $mailgun_domain }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">{{ __('Sender') }}</dt>
                        <dd class="font-medium text-zinc-900">{{ $sender_name }} &lt;{{ $sender_email }}&gt;</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500">{{ __('API key') }}</dt>
                        <dd class="font-medium text-zinc-900">{{ str_repeat('•', 12) }}{{ substr($mailgun_api_key, -4) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex gap-3">
                <flux:button variant="outline" wire:click="previousStep" class="w-full">
                    {{ __('Back') }}
                </flux:button>
                <flux:button variant="primary" wire:click="complete" class="w-full">
                    {{ __('Get started') }}
                </flux:button>
            </div>
        </div>
    @endif
</div>
