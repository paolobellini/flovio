<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 antialiased">
        {{-- Header --}}
        <header class="sticky top-0 z-40 border-b border-zinc-200 bg-white">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5" wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-wine-800 text-white">
                        <x-app-logo-icon class="h-5 w-5 fill-current" />
                    </span>
                    <span class="text-lg font-semibold tracking-tight text-zinc-900">Flovio</span>
                </a>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    <flux:dropdown position="bottom" align="end">
                        <flux:profile
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                            icon-trailing="chevron-down"
                            data-test="sidebar-menu-button"
                        />

                        <flux:menu>
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                            <flux:menu.separator />
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>
                            <flux:menu.separator />
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer" data-test="logout-button">
                                    {{ __('Log out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </header>

        {{-- Main content --}}
        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>

        {{-- Floating side navigation --}}
        <nav class="fixed left-5 top-1/2 z-50 hidden -translate-y-1/2 lg:block">
            <div class="flex flex-col items-center gap-2 rounded-full border border-zinc-200/80 bg-white/90 p-2 shadow-xl shadow-zinc-900/8 backdrop-blur-sm">
                <flux:tooltip content="{{ __('Dashboard') }}" position="right">
                    <a
                        href="{{ route('dashboard') }}"
                        wire:navigate
                        @class([
                            'flex h-11 w-11 items-center justify-center rounded-full transition',
                            'bg-wine-800 text-white shadow-md shadow-wine-900/20' => request()->routeIs('dashboard'),
                            'text-zinc-400 hover:bg-zinc-100 hover:text-wine-800' => !request()->routeIs('dashboard'),
                        ])
                    >
                        <flux:icon.home variant="mini" />
                    </a>
                </flux:tooltip>

                <flux:tooltip content="{{ __('Contacts') }}" position="right">
                    <a
                        href="{{ route('contacts.index') }}"
                        wire:navigate
                        @class([
                            'flex h-11 w-11 items-center justify-center rounded-full transition',
                            'bg-wine-800 text-white shadow-md shadow-wine-900/20' => request()->routeIs('contacts.*'),
                            'text-zinc-400 hover:bg-zinc-100 hover:text-wine-800' => !request()->routeIs('contacts.*'),
                        ])
                    >
                        <flux:icon.users variant="mini" />
                    </a>
                </flux:tooltip>

                <flux:tooltip content="{{ __('Lists') }}" position="right">
                    <a
                        href="{{ route('lists.index') }}"
                        wire:navigate
                        @class([
                            'flex h-11 w-11 items-center justify-center rounded-full transition',
                            'bg-wine-800 text-white shadow-md shadow-wine-900/20' => request()->routeIs('lists.*'),
                            'text-zinc-400 hover:bg-zinc-100 hover:text-wine-800' => !request()->routeIs('lists.*'),
                        ])
                    >
                        <flux:icon.rectangle-stack variant="mini" />
                    </a>
                </flux:tooltip>

                <flux:tooltip content="{{ __('Templates') }}" position="right">
                    <a
                        href="{{ route('templates.index') }}"
                        wire:navigate
                        @class([
                            'flex h-11 w-11 items-center justify-center rounded-full transition',
                            'bg-wine-800 text-white shadow-md shadow-wine-900/20' => request()->routeIs('templates.*'),
                            'text-zinc-400 hover:bg-zinc-100 hover:text-wine-800' => !request()->routeIs('templates.*'),
                        ])
                    >
                        <flux:icon.document-text variant="mini" />
                    </a>
                </flux:tooltip>

                <flux:tooltip content="{{ __('Settings') }}" position="right">
                    <a
                        href="{{ route('profile.edit') }}"
                        wire:navigate
                        @class([
                            'flex h-11 w-11 items-center justify-center rounded-full transition',
                            'bg-wine-800 text-white shadow-md shadow-wine-900/20' => request()->routeIs('profile.edit', 'ai.edit', 'mailgun.edit', 'security.edit'),
                            'text-zinc-400 hover:bg-zinc-100 hover:text-wine-800' => !request()->routeIs('profile.edit', 'ai.edit', 'mailgun.edit', 'security.edit'),
                        ])
                    >
                        <flux:icon.cog variant="mini" />
                    </a>
                </flux:tooltip>
            </div>
        </nav>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
