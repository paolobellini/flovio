<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureUserIsOnboarded;
use App\Http\Middleware\RedirectIfOnboarded;
use App\Livewire\Contacts\Index as ContactsIndex;
use App\Livewire\Onboarding\Wizard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('onboarding', Wizard::class)->name('onboarding')->middleware(RedirectIfOnboarded::class);
    Route::view('dashboard', 'dashboard')->name('dashboard')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('contacts', ContactsIndex::class)->name('contacts.index')->middleware(EnsureUserIsOnboarded::class);
});

require __DIR__.'/settings.php';
