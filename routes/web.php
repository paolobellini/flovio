<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureUserIsOnboarded;
use App\Http\Middleware\RedirectIfOnboarded;
use App\Livewire\Contacts\Index as ContactsIndex;
use App\Livewire\Contacts\Show as ContactsShow;
use App\Livewire\Lists\Index as ListsIndex;
use App\Livewire\Lists\Show as ListsShow;
use App\Livewire\Onboarding\Wizard;
use App\Livewire\Templates\Editor as TemplatesEditor;
use App\Livewire\Templates\Index as TemplatesIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('onboarding', Wizard::class)->name('onboarding')->middleware(RedirectIfOnboarded::class);
    Route::view('dashboard', 'dashboard')->name('dashboard')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('contacts', ContactsIndex::class)->name('contacts.index')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('contacts/{contact}', ContactsShow::class)->name('contacts.show')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('lists', ListsIndex::class)->name('lists.index')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('lists/{list}', ListsShow::class)->name('lists.show')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('templates', TemplatesIndex::class)->name('templates.index')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('templates/create', TemplatesEditor::class)->name('templates.create')->middleware(EnsureUserIsOnboarded::class);
    Route::livewire('templates/{id}/edit', TemplatesEditor::class)->name('templates.edit')->middleware(EnsureUserIsOnboarded::class);
});

require __DIR__.'/settings.php';
