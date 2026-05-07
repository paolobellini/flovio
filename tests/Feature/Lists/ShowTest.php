<?php

declare(strict_types=1);

use App\Livewire\Lists\Show;
use App\Models\Contact;
use App\Models\MailingList;
use App\Models\User;
use Livewire\Livewire;

test('list show page can be rendered', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();

    $this->actingAs($user)
        ->get(route('lists.show', $list))
        ->assertOk()
        ->assertSee($list->name);
});

test('list show page displays members', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $contact = Contact::factory()->create(['name' => 'Marco Rossi']);
    $list->contacts()->attach($contact);

    $this->actingAs($user);

    Livewire::test(Show::class, ['list' => $list])
        ->assertSee('Marco Rossi');
});

test('members can be searched', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $marco = Contact::factory()->create(['name' => 'Marco Rossi']);
    $laura = Contact::factory()->create(['name' => 'Laura Bianchi']);
    $list->contacts()->attach([$marco->id, $laura->id]);

    $this->actingAs($user);

    Livewire::test(Show::class, ['list' => $list])
        ->set('memberSearch', 'Marco')
        ->assertSee('Marco Rossi')
        ->assertDontSee('Laura Bianchi');
});

test('members can be added to list', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $contact = Contact::factory()->create();

    $this->actingAs($user);

    Livewire::test(Show::class, ['list' => $list])
        ->call('openAddMembers')
        ->set('selectedContacts', [$contact->id])
        ->call('addMembers')
        ->assertHasNoErrors();

    expect($list->contacts()->count())->toBe(1);
});

test('member can be removed from list', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $contact = Contact::factory()->create();
    $list->contacts()->attach($contact);

    $this->actingAs($user);

    Livewire::test(Show::class, ['list' => $list])
        ->call('removeMember', $contact->id)
        ->assertHasNoErrors();

    expect($list->contacts()->count())->toBe(0);
});

test('list can be edited', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create(['name' => 'Old Name']);

    $this->actingAs($user);

    Livewire::test(Show::class, ['list' => $list])
        ->call('edit')
        ->set('name', 'New Name')
        ->set('icon', 'star')
        ->set('color', 'blue')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('mailing_lists', [
        'id' => $list->id,
        'name' => 'New Name',
        'icon' => 'star',
        'color' => 'blue',
    ]);
});

test('list can be deleted', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $contact = Contact::factory()->create();
    $list->contacts()->attach($contact);

    $this->actingAs($user);

    Livewire::test(Show::class, ['list' => $list])
        ->call('confirmDelete')
        ->call('delete')
        ->assertRedirect(route('lists.index'));

    $this->assertDatabaseMissing('mailing_lists', ['id' => $list->id]);
    expect(Contact::query()->count())->toBe(1);
});

test('stats show correct member count', function () {
    $user = User::factory()->onboarded()->create();
    $list = MailingList::factory()->create();
    $contacts = Contact::factory()->count(3)->create();
    $list->contacts()->attach($contacts);

    $this->actingAs($user);

    $component = Livewire::test(Show::class, ['list' => $list]);

    expect($component->get('stats')['members'])->toBe(3);
});
