<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')

        <style>
            @keyframes float-slow {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
            }
            @keyframes float-slower {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(14px) scale(1.02); }
            }
            @keyframes pulse-glow {
                0%, 100% { opacity: 0.08; }
                50% { opacity: 0.15; }
            }
            .float-slow { animation: float-slow 8s ease-in-out infinite; }
            .float-slower { animation: float-slower 12s ease-in-out infinite; }
            .pulse-glow { animation: pulse-glow 6s ease-in-out infinite; }
        </style>
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="relative grid h-dvh lg:grid-cols-2">
            {{-- Left panel: Form --}}
            <div class="flex items-center justify-center px-6 py-12 sm:px-8 lg:px-12">
                <div class="w-full max-w-sm">
                    {{-- Mobile logo --}}
                    <a href="{{ route('home') }}" class="mb-8 flex flex-col items-center gap-2 lg:hidden" wire:navigate>
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-wine-800 text-white">
                            <x-app-logo-icon class="h-7 w-7 fill-current" />
                        </span>
                        <span class="text-lg font-semibold tracking-tight text-zinc-900">{{ config('app.name', 'Laravel') }}</span>
                    </a>

                    {{ $slot }}
                </div>
            </div>

            {{-- Right panel --}}
            <div class="relative hidden overflow-hidden lg:flex lg:items-center lg:justify-center"
                style="background: linear-gradient(160deg, var(--color-wine-950) 0%, var(--color-wine-900) 30%, var(--color-wine-800) 70%, var(--color-wine-700) 100%);"
            >
                {{-- Animated blobs --}}
                <div class="float-slow absolute -top-20 right-10 h-72 w-72 rounded-full blur-3xl"
                    style="background: var(--color-wine-600); opacity: 0.12;"
                ></div>
                <div class="float-slower absolute bottom-20 -left-16 h-96 w-96 rounded-full blur-3xl"
                    style="background: var(--color-wine-500); opacity: 0.1;"
                ></div>
                <div class="pulse-glow absolute top-1/2 left-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full blur-3xl"
                    style="background: radial-gradient(circle, var(--color-wine-400), transparent 70%);"
                ></div>

                {{-- Grid pattern overlay --}}
                <div class="absolute inset-0 opacity-[0.03]"
                    style="background-image: radial-gradient(circle, rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 32px 32px;"
                ></div>

                {{-- Content --}}
                <div class="relative z-10 flex flex-col items-center px-12 text-center">
                    {{-- Brand --}}
                    <a href="{{ route('home') }}" class="group mb-14 flex items-center gap-3" wire:navigate>
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md transition group-hover:border-white/20 group-hover:bg-white/10">
                            <x-app-logo-icon class="h-6 w-6 fill-current text-white" />
                        </span>
                        <span class="text-xl font-semibold tracking-tight text-white">{{ config('app.name', 'Laravel') }}</span>
                    </a>

                    {{-- Headline --}}
                    <h2 class="max-w-md text-4xl font-bold leading-[1.15] tracking-tight text-white">
                        {{ __('Your campaigns, your rules.') }}
                    </h2>
                    <p class="mt-4 max-w-sm text-base leading-relaxed text-white/50">
                        {{ __('Self-hosted email marketing with AI-powered design, automation, and real-time analytics — all through the Mailgun API.') }}
                    </p>

                    {{-- Feature pills --}}
                    <div class="mt-12 flex flex-wrap items-center justify-center gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80 backdrop-blur-sm">
                            <flux:icon.sparkles variant="micro" class="text-wine-300" />
                            {{ __('AI Design') }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80 backdrop-blur-sm">
                            <flux:icon.clock variant="micro" class="text-wine-300" />
                            {{ __('Automation') }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80 backdrop-blur-sm">
                            <flux:icon.chart-bar variant="micro" class="text-wine-300" />
                            {{ __('Tracking') }}
                        </span>

                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white/80 backdrop-blur-sm">
                            <flux:icon.envelope variant="micro" class="text-wine-300" />
                            {{ __('Mailgun API') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
