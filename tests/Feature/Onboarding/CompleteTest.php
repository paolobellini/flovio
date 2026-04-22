<?php

declare(strict_types=1);

use App\Livewire\Onboarding\Wizard;
use App\Models\User;
use Livewire\Livewire;

test('full onboarding flow persists data to database', function () {
    $user = User::factory()->create(['name' => 'Paolo']);

    $this->actingAs($user);

    Livewire::test(Wizard::class)
        ->assertSet('name', 'Paolo')
        ->set('name', 'Paolo Bellini')
        ->set('company_name', 'My Agency')
        ->set('timezone', 'Europe/Rome')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->set('mailgun_api_key', 'key-abc12345678')
        ->set('mailgun_domain', 'mg.example.com')
        ->set('sender_name', 'My Agency')
        ->set('sender_email', 'hello@example.com')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->call('complete')
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Paolo Bellini',
        'company_name' => 'My Agency',
        'timezone' => 'Europe/Rome',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);

    $user->refresh();
    expect($user->onboarded_at)->not->toBeNull();

    $this->assertDatabaseHas('smtp_settings', [
        'user_id' => $user->id,
        'domain' => 'mg.example.com',
        'sender_name' => 'My Agency',
        'sender_email' => 'hello@example.com',
    ]);

    expect($user->smtpSetting->api_key)->toBe('key-abc12345678');

    $this->assertDatabaseHas('ai_settings', [
        'user_id' => $user->id,
        'chat_model' => 'gemini-2.5-flash',
        'image_model' => 'gemini-2.5-flash-image',
        'content_model' => 'gemini-2.5-flash',
    ]);
});
