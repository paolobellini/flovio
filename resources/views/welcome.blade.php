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
                50% { opacity: 0.18; }
            }
            .float-slow { animation: float-slow 8s ease-in-out infinite; }
            .float-slower { animation: float-slower 12s ease-in-out infinite; }
            .pulse-glow { animation: pulse-glow 6s ease-in-out infinite; }
        </style>
    </head>
    <body class="min-h-screen bg-white text-zinc-900 antialiased">
        {{-- Background gradient + blobs --}}
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[700px] overflow-hidden"
            style="background: linear-gradient(180deg, var(--color-wine-50) 0%, transparent 100%);"
        >
            <div class="float-slow absolute -top-32 -right-20 h-96 w-96 rounded-full blur-3xl"
                style="background: var(--color-wine-300); opacity: 0.35;"
            ></div>
            <div class="float-slower absolute top-40 -left-24 h-[28rem] w-[28rem] rounded-full blur-3xl"
                style="background: var(--color-wine-200); opacity: 0.5;"
            ></div>
            <div class="pulse-glow absolute top-32 left-1/2 h-[500px] w-[500px] -translate-x-1/2 rounded-full blur-3xl"
                style="background: radial-gradient(circle, var(--color-wine-400), transparent 70%);"
            ></div>
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: radial-gradient(circle, rgba(123,45,66,0.8) 1px, transparent 1px); background-size: 32px 32px;"
            ></div>
        </div>

        {{-- Header --}}
        <header class="relative z-10">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5" wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-wine-800 text-white">
                        <x-app-logo-icon class="h-5 w-5 fill-current" />
                    </span>
                    <span class="text-lg font-semibold tracking-tight text-zinc-900">{{ config('app.name', 'Flovio') }}</span>
                </a>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center rounded-lg bg-wine-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-wine-900"
                                wire:navigate
                            >
                                {{ __('Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="hidden rounded-lg px-4 py-2 text-sm font-medium text-zinc-700 transition hover:text-zinc-900 sm:inline-flex"
                                wire:navigate
                            >
                                {{ __('Log in') }}
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center rounded-lg bg-wine-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-wine-900"
                                    wire:navigate
                                >
                                    {{ __('Get started') }}
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="relative z-10">
            {{-- Hero --}}
            <section class="mx-auto max-w-6xl px-6 pt-16 pb-24 lg:px-8 lg:pt-24 lg:pb-32">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="inline-flex items-center gap-2 rounded-full border border-wine-200 bg-wine-50/80 px-4 py-1.5 text-xs font-medium text-wine-800 backdrop-blur-sm">
                        <flux:icon.sparkles variant="micro" class="text-wine-600" />
                        {{ __('Self-hosted email marketing, powered by Mailgun') }}
                    </span>

                    <h1 class="mt-6 text-5xl font-bold leading-[1.05] tracking-tight text-zinc-900 sm:text-6xl lg:text-7xl">
                        {{ __('Your campaigns,') }}
                        <span class="block text-wine-800">{{ __('your rules.') }}</span>
                    </h1>

                    <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-zinc-600">
                        {{ __('Design newsletters with AI, schedule sends, track opens and clicks in real time, and review content automatically before delivery — all from one self-hosted platform.') }}
                    </p>

                    <div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-wine-800 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-wine-800/20 transition hover:bg-wine-900 sm:w-auto"
                                wire:navigate
                            >
                                {{ __('Go to dashboard') }}
                                <flux:icon.arrow-right variant="micro" />
                            </a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-wine-800 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-wine-800/20 transition hover:bg-wine-900 sm:w-auto"
                                    wire:navigate
                                >
                                    {{ __('Start free') }}
                                    <flux:icon.arrow-right variant="micro" />
                                </a>
                            @endif
                            <a href="{{ route('login') }}"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-zinc-200 bg-white/70 px-6 py-3 text-base font-semibold text-zinc-900 backdrop-blur-sm transition hover:border-zinc-300 hover:bg-white sm:w-auto"
                                wire:navigate
                            >
                                {{ __('Log in') }}
                            </a>
                        @endauth
                    </div>

                    {{-- Feature pills --}}
                    <div class="mt-12 flex flex-wrap items-center justify-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white/70 px-3 py-1.5 text-xs font-medium text-zinc-700 backdrop-blur-sm">
                            <flux:icon.sparkles variant="micro" class="text-wine-700" />
                            {{ __('AI Design') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white/70 px-3 py-1.5 text-xs font-medium text-zinc-700 backdrop-blur-sm">
                            <flux:icon.clock variant="micro" class="text-wine-700" />
                            {{ __('Automation') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white/70 px-3 py-1.5 text-xs font-medium text-zinc-700 backdrop-blur-sm">
                            <flux:icon.chart-bar variant="micro" class="text-wine-700" />
                            {{ __('Tracking') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white/70 px-3 py-1.5 text-xs font-medium text-zinc-700 backdrop-blur-sm">
                            <flux:icon.shield-check variant="micro" class="text-wine-700" />
                            {{ __('Content Review') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white/70 px-3 py-1.5 text-xs font-medium text-zinc-700 backdrop-blur-sm">
                            <flux:icon.envelope variant="micro" class="text-wine-700" />
                            {{ __('Mailgun API') }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- Features grid --}}
            <section class="mx-auto max-w-6xl px-6 pb-24 lg:px-8">
                <div class="mx-auto mb-16 max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                        {{ __('Everything you need to ship campaigns') }}
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-zinc-600">
                        {{ __('Built for agencies and businesses that need full control over their email marketing.') }}
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @php
                        $features = [
                            ['icon' => 'sparkles', 'title' => __('AI-powered design'), 'body' => __('Generate on-brand newsletters in minutes with AI assistance built into the editor.')],
                            ['icon' => 'clock', 'title' => __('Schedule & automate'), 'body' => __('Plan single sends or trigger automated sequences based on contact engagement.')],
                            ['icon' => 'chart-bar', 'title' => __('Real-time analytics'), 'body' => __('Track opens, clicks, bounces, and unsubscribes the moment they happen.')],
                            ['icon' => 'users', 'title' => __('Smart segmentation'), 'body' => __('Auto-segment contacts based on engagement patterns aggregated from every send.')],
                            ['icon' => 'shield-check', 'title' => __('Automatic content review'), 'body' => __('AI reviews your content before delivery to catch typos, broken links, and tone issues.')],
                            ['icon' => 'envelope', 'title' => __('Mailgun delivery'), 'body' => __('Send through your own Mailgun account. Your contacts and lists stay on your database.')],
                        ];
                    @endphp

                    @foreach ($features as $feature)
                        <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 transition hover:border-wine-200 hover:shadow-lg hover:shadow-wine-100/40">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-wine-50 text-wine-800 transition group-hover:bg-wine-100">
                                <flux:icon :name="$feature['icon']" variant="micro" />
                            </span>
                            <h3 class="mt-4 text-base font-semibold text-zinc-900">{{ $feature['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-zinc-600">{{ $feature['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- CTA --}}
            @guest
                <section class="mx-auto max-w-6xl px-6 pb-24 lg:px-8">
                    <div class="relative overflow-hidden rounded-3xl px-8 py-16 text-center sm:px-16"
                        style="background: linear-gradient(160deg, var(--color-wine-950) 0%, var(--color-wine-900) 30%, var(--color-wine-800) 70%, var(--color-wine-700) 100%);"
                    >
                        <div class="float-slow absolute -top-20 right-10 h-72 w-72 rounded-full blur-3xl"
                            style="background: var(--color-wine-600); opacity: 0.18;"
                        ></div>
                        <div class="float-slower absolute -bottom-16 -left-16 h-96 w-96 rounded-full blur-3xl"
                            style="background: var(--color-wine-500); opacity: 0.15;"
                        ></div>
                        <div class="absolute inset-0 opacity-[0.04]"
                            style="background-image: radial-gradient(circle, rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 32px 32px;"
                        ></div>

                        <div class="relative z-10 mx-auto max-w-2xl">
                            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                                {{ __('Ready to take control of your email marketing?') }}
                            </h2>
                            <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-white/70">
                                {{ __('Self-host Flovio, plug in your Mailgun account, and start sending in minutes.') }}
                            </p>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="mt-8 inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-base font-semibold text-wine-900 shadow-lg transition hover:bg-wine-50"
                                    wire:navigate
                                >
                                    {{ __('Create your account') }}
                                    <flux:icon.arrow-right variant="micro" />
                                </a>
                            @endif
                        </div>
                    </div>
                </section>
            @endguest
        </main>

        {{-- Footer --}}
        <footer class="relative z-10 border-t border-zinc-100">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 py-8 text-sm text-zinc-500 sm:flex-row lg:px-8">
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-wine-800 text-white">
                        <x-app-logo-icon class="h-3.5 w-3.5 fill-current" />
                    </span>
                    <span>&copy; {{ date('Y') }} {{ config('app.name', 'Flovio') }}</span>
                </div>
                <p>{{ __('Built with Laravel, Livewire, and Flux UI.') }}</p>
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
