<x-layouts::auth.split :title="__('Two-factor authentication')">
    <div class="flex flex-col gap-8">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $dispatch('clear-2fa-auth-code');

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <div class="flex flex-col gap-1 text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Authentication code') }}</h1>
                    <p class="text-sm text-zinc-500">{{ __('Enter the authentication code provided by your authenticator application.') }}</p>
                </div>
            </div>

            <div x-show="showRecoveryInput">
                <div class="flex flex-col gap-1 text-center">
                    <h1 class="text-2xl font-bold tracking-tight text-zinc-900">{{ __('Recovery code') }}</h1>
                    <p class="text-sm text-zinc-500">{{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="mt-8 space-y-5 text-center">
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center">
                            <flux:otp
                                x-model="code"
                                length="6"
                                name="code"
                                label="OTP Code"
                                label:sr-only
                                class="mx-auto"
                             />
                        </div>
                    </div>

                    <div x-show="showRecoveryInput">
                        <flux:input
                            type="text"
                            name="recovery_code"
                            x-ref="recovery_code"
                            x-bind:required="showRecoveryInput"
                            autocomplete="one-time-code"
                            x-model="recovery_code"
                        />

                        @error('recovery_code')
                            <flux:text color="red" class="mt-2">
                                {{ $message }}
                            </flux:text>
                        @enderror
                    </div>

                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Continue') }}
                    </flux:button>
                </div>

                <div class="mt-5 space-x-0.5 text-sm leading-5 text-center text-zinc-500">
                    <span>{{ __('or you can') }}</span>
                    <span class="inline font-medium underline cursor-pointer text-zinc-700" x-show="!showRecoveryInput" @click="toggleInput()">{{ __('login using a recovery code') }}</span>
                    <span class="inline font-medium underline cursor-pointer text-zinc-700" x-show="showRecoveryInput" @click="toggleInput()">{{ __('login using an authentication code') }}</span>
                </div>
            </form>
        </div>
    </div>
</x-layouts::auth.split>
