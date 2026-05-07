<?php

declare(strict_types=1);

use App\Livewire\Templates\Index;
use App\Models\Template;
use App\Models\User;
use Livewire\Livewire;

test('templates page can be rendered', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user)
        ->get(route('templates.index'))
        ->assertOk()
        ->assertSee(__('Templates'));
});

test('templates page displays templates', function () {
    $user = User::factory()->onboarded()->create();
    Template::factory()->create(['name' => 'Newsletter Classica']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertSee('Newsletter Classica');
});

test('templates can be searched', function () {
    $user = User::factory()->onboarded()->create();
    Template::factory()->create(['name' => 'Newsletter Classica']);
    Template::factory()->create(['name' => 'Promo Stagionale']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('search', 'News')
        ->assertSee('Newsletter Classica')
        ->assertDontSee('Promo Stagionale');
});

test('templates page shows empty state when no templates', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertSee(__('No templates yet.'));
});
