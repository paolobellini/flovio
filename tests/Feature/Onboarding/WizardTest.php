<?php

declare(strict_types=1);

use App\Livewire\Onboarding\Wizard;
use App\Models\User;
use Livewire\Livewire;

test('onboarding page can be rendered', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('onboarding'))->assertOk();
});

test('onboarding requires authentication', function () {
    $this->get(route('onboarding'))->assertRedirect(route('login'));
});

test('step 1 pre-fills name from authenticated user', function () {
    $user = User::factory()->create(['name' => 'John Doe']);

    $this->actingAs($user);

    Livewire::test(Wizard::class)
        ->assertSet('name', 'John Doe')
        ->assertSet('currentStep', 1);
});

test('step 1 validates required fields', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('name', '')
        ->set('timezone', '')
        ->call('nextStep')
        ->assertHasErrors(['name', 'timezone'])
        ->assertSet('currentStep', 1);
});

test('step 1 allows empty company name', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('name', 'Jane Doe')
        ->set('company_name', '')
        ->set('timezone', 'Europe/Rome')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});

test('step 1 advances to step 2 with valid data', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('name', 'Jane Doe')
        ->set('company_name', 'Acme Inc')
        ->set('timezone', 'Europe/Rome')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});

test('step 1 validates timezone is valid', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('name', 'Jane Doe')
        ->set('timezone', 'Invalid/Zone')
        ->call('nextStep')
        ->assertHasErrors(['timezone']);
});

test('step 2 validates required fields', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('currentStep', 2)
        ->set('mailgun_api_key', '')
        ->set('mailgun_domain', '')
        ->set('sender_name', '')
        ->set('sender_email', '')
        ->call('nextStep')
        ->assertHasErrors(['mailgun_api_key', 'mailgun_domain', 'sender_name', 'sender_email'])
        ->assertSet('currentStep', 2);
});

test('step 2 validates sender email format', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('currentStep', 2)
        ->set('mailgun_api_key', 'key-abc123')
        ->set('mailgun_domain', 'mg.example.com')
        ->set('sender_name', 'Acme')
        ->set('sender_email', 'not-an-email')
        ->call('nextStep')
        ->assertHasErrors(['sender_email']);
});

test('step 2 advances to step 3 with valid data', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('currentStep', 2)
        ->set('mailgun_api_key', 'key-abc123')
        ->set('mailgun_domain', 'mg.example.com')
        ->set('sender_name', 'Acme')
        ->set('sender_email', 'hello@example.com')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3);
});

test('user can navigate back to previous steps', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('currentStep', 3)
        ->call('previousStep')
        ->assertSet('currentStep', 2)
        ->call('previousStep')
        ->assertSet('currentStep', 1);
});

test('user cannot skip ahead via goToStep', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->assertSet('currentStep', 1)
        ->call('goToStep', 3)
        ->assertSet('currentStep', 1);
});

test('user can go back to completed steps via goToStep', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('currentStep', 3)
        ->call('goToStep', 1)
        ->assertSet('currentStep', 1);
});

test('completing onboarding redirects to dashboard', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(Wizard::class)
        ->set('currentStep', 3)
        ->call('complete')
        ->assertRedirect(route('dashboard'));
});

test('full wizard flow works end to end', function () {
    $user = User::factory()->create(['name' => 'Paolo']);

    $this->actingAs($user);

    Livewire::test(Wizard::class)
        ->assertSet('name', 'Paolo')
        ->set('company_name', 'My Agency')
        ->set('timezone', 'Europe/Rome')
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->set('mailgun_api_key', 'key-abc123')
        ->set('mailgun_domain', 'mg.example.com')
        ->set('sender_name', 'My Agency')
        ->set('sender_email', 'hello@example.com')
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->call('complete')
        ->assertRedirect(route('dashboard'));
});
