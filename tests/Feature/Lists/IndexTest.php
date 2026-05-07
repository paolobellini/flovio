<?php

declare(strict_types=1);

use App\Livewire\Lists\Index;
use App\Models\Contact;
use App\Models\MailingList;
use App\Models\User;
use Livewire\Livewire;

test('lists page can be rendered', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user)
        ->get(route('lists.index'))
        ->assertOk()
        ->assertSee(__('Lists'));
});

test('lists page displays lists', function () {
    $user = User::factory()->onboarded()->create();
    MailingList::factory()->create(['name' => 'Newsletter']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertSee('Newsletter');
});

test('lists can be searched', function () {
    $user = User::factory()->onboarded()->create();
    MailingList::factory()->create(['name' => 'Newsletter']);
    MailingList::factory()->create(['name' => 'VIP Customers']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('search', 'News')
        ->assertSee('Newsletter')
        ->assertDontSee('VIP Customers');
});

test('list can be created', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->set('name', 'New List')
        ->set('description', 'A test list')
        ->set('icon', 'star')
        ->set('color', 'blue')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('mailing_lists', [
        'name' => 'New List',
        'icon' => 'star',
        'color' => 'blue',
    ]);
});

test('list can be deleted from index', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $contact = Contact::factory()->create();
    $list->contacts()->attach($contact);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('confirmDelete', $list->id)
        ->assertSet('confirmingDelete.id', $list->id)
        ->call('delete')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('mailing_lists', ['id' => $list->id]);
    expect(Contact::query()->count())->toBe(1);
});

test('stats show correct counts', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $contacts = Contact::factory()->count(5)->create();
    $list->contacts()->attach($contacts);

    $this->actingAs($user);

    $component = Livewire::test(Index::class);

    expect($component->get('stats'))
        ->total->toBe(1)
        ->and($component->get('stats')['total_members'])->toBe(5);
});

test('list form validates required fields', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->set('name', '')
        ->call('save')
        ->assertHasErrors('name');
});
