<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureUserIsOnboarded;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Security;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', EnsureUserIsOnboarded::class])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
});

Route::middleware(['auth', 'verified', EnsureUserIsOnboarded::class])->group(function () {
    Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::livewire('settings/security', Security::class)->name('security.edit');
});
