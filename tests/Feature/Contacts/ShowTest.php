<?php

declare(strict_types=1);

use App\Livewire\Contacts\Show;
use App\Models\Contact;
use App\Models\MailingList;
use App\Models\User;
use Livewire\Livewire;

test('contact show page can be rendered', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create();

    $this->actingAs($user)
        ->get(route('contacts.show', $contact))
        ->assertOk()
        ->assertSee($contact->name);
});

test('contact show page displays lists', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create();
    $list = MailingList::factory()->create(['name' => 'Newsletter']);
    $contact->mailingLists()->attach($list);

    $this->actingAs($user);

    $component = Livewire::test(Show::class, ['contact' => $contact]);

    expect($component->get('contactLists'))->toHaveCount(1);
    $component->assertSee('Newsletter');
});

test('contact can be added to lists', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create();
    $list = MailingList::factory()->create();

    $this->actingAs($user);

    Livewire::test(Show::class, ['contact' => $contact])
        ->call('openAddToList')
        ->set('selectedLists', [$list->id])
        ->call('addToLists')
        ->assertHasNoErrors();

    expect($contact->mailingLists()->count())->toBe(1);
});

test('contact can be removed from a list', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create();
    $list = MailingList::factory()->create();
    $contact->mailingLists()->attach($list);

    $this->actingAs($user);

    Livewire::test(Show::class, ['contact' => $contact])
        ->call('removeFromList', $list->id)
        ->assertHasNoErrors();

    expect($contact->mailingLists()->count())->toBe(0);
});

test('available lists excludes already assigned lists', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create();
    $assigned = MailingList::factory()->create();
    $available = MailingList::factory()->create();
    $contact->mailingLists()->attach($assigned);

    $this->actingAs($user);

    $component = Livewire::test(Show::class, ['contact' => $contact]);

    $availableIds = $component->get('availableLists')->pluck('id')->all();

    expect($availableIds)->toContain($available->id)
        ->and($availableIds)->not->toContain($assigned->id);
});
