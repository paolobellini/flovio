<?php

declare(strict_types=1);

use App\Livewire\Templates\Editor;
use App\Models\Template;
use App\Models\User;
use Livewire\Livewire;

test('create template page can be rendered', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user)
        ->get(route('templates.create'))
        ->assertOk()
        ->assertSee(__('New template'));
});

test('edit template page can be rendered', function () {
    $user = User::factory()->onboarded()->create();
    $template = Template::factory()->create();

    $this->actingAs($user)
        ->get(route('templates.edit', $template))
        ->assertOk()
        ->assertSee($template->name);
});

test('template can be created', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    Livewire::test(Editor::class)
        ->set('name', 'Newsletter')
        ->set('description', 'Weekly newsletter')
        ->set('primary_color', '#7B2D42')
        ->set('layout', 'single')
        ->set('tone', 'professional')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('templates', [
        'name' => 'Newsletter',
        'layout' => 'single',
    ]);
});

test('template can be updated', function () {
    $user = User::factory()->onboarded()->create();
    $template = Template::factory()->create(['name' => 'Old Name']);

    $this->actingAs($user);

    Livewire::test(Editor::class, ['template' => $template])
        ->assertSet('name', 'Old Name')
        ->set('name', 'New Name')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('templates', [
        'id' => $template->id,
        'name' => 'New Name',
    ]);
});

test('template form validates required fields', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    Livewire::test(Editor::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors('name');
});

test('template can be deleted', function () {
    $user = User::factory()->onboarded()->create();
    $template = Template::factory()->create();

    $this->actingAs($user);

    Livewire::test(Editor::class, ['template' => $template])
        ->call('confirmDelete')
        ->call('delete')
        ->assertRedirect(route('templates.index'));

    $this->assertDatabaseMissing('templates', ['id' => $template->id]);
});
