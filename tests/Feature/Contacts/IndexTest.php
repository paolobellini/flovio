<?php

declare(strict_types=1);

use App\Livewire\Contacts\Index;
use App\Models\Contact;
use App\Models\User;
use Livewire\Livewire;

test('contacts page can be rendered', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user)
        ->get(route('contacts.index'))
        ->assertOk()
        ->assertSee(__('Contacts'));
});

test('contacts page lists contacts', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create(['name' => 'Marco Rossi']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertSee('Marco Rossi');
});

test('contacts can be searched by name', function () {
    $user = User::factory()->onboarded()->create();
    Contact::factory()->create(['name' => 'Marco Rossi']);
    Contact::factory()->create(['name' => 'Laura Bianchi']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('search', 'Marco')
        ->assertSee('Marco Rossi')
        ->assertDontSee('Laura Bianchi');
});

test('contacts can be searched by email', function () {
    $user = User::factory()->onboarded()->create();
    Contact::factory()->create(['name' => 'Marco Rossi', 'email' => 'marco@example.com']);
    Contact::factory()->create(['name' => 'Laura Bianchi', 'email' => 'laura@example.com']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('search', 'laura')
        ->assertDontSee('Marco Rossi')
        ->assertSee('Laura Bianchi');
});

test('contacts can be filtered by status', function () {
    $user = User::factory()->onboarded()->create();
    Contact::factory()->create(['name' => 'Marco Rossi']);
    Contact::factory()->unsubscribed()->create(['name' => 'Laura Bianchi']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('status', 'unsubscribed')
        ->assertDontSee('Marco Rossi')
        ->assertSee('Laura Bianchi');
});

test('contacts are paginated', function () {
    $user = User::factory()->onboarded()->create();
    Contact::factory()->count(15)->create();

    $this->actingAs($user);

    $component = Livewire::test(Index::class);

    expect($component->get('contacts'))->toHaveCount(10);
});

test('stats show correct counts', function () {
    $user = User::factory()->onboarded()->create();
    Contact::factory()->count(3)->create();
    Contact::factory()->unsubscribed()->count(2)->create();

    $this->actingAs($user);

    $component = Livewire::test(Index::class);

    expect($component->get('stats'))
        ->total->toBe(5)
        ->subscribed->toBe(3)
        ->unsubscribed->toBe(2);
});

test('contact can be deleted', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('confirmDelete', $contact->id)
        ->assertSet('confirmingDelete.id', $contact->id)
        ->call('delete')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
});

test('contact can be created', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->assertSet('editing', null)
        ->set('name', 'Marco Rossi')
        ->set('email', 'marco@example.com')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('contacts', [
        'name' => 'Marco Rossi',
        'email' => 'marco@example.com',
    ]);
});

test('contact can be edited', function () {
    $user = User::factory()->onboarded()->create();
    $contact = Contact::factory()->create(['name' => 'Marco Rossi']);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('edit', $contact->id)
        ->assertSet('name', 'Marco Rossi')
        ->set('name', 'Marco Bianchi')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'name' => 'Marco Bianchi',
    ]);
});

test('contact form validates required fields', function (string $field) {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('create')
        ->set($field, '')
        ->call('save')
        ->assertHasErrors($field);
})->with([
    'name' => ['name'],
    'email' => ['email'],
]);

test('contacts can be bulk deleted', function () {
    $user = User::factory()->onboarded()->create();
    $contacts = Contact::factory()->count(3)->create();
    $kept = Contact::factory()->create();

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('selected', $contacts->pluck('id')->all())
        ->call('confirmBulkDelete')
        ->call('bulkDelete')
        ->assertSet('selected', [])
        ->assertHasNoErrors();

    expect(Contact::query()->count())->toBe(1)
        ->and(Contact::query()->first()->id)->toBe($kept->id);
});
