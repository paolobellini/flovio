<?php

declare(strict_types=1);

use App\Livewire\Settings\Mailgun;
use App\Models\SmtpSetting;
use App\Models\User;
use Livewire\Livewire;

test('mailgun settings page can be rendered', function () {
    $user = User::factory()->onboarded()->create();
    SmtpSetting::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('mailgun.edit'))
        ->assertOk()
        ->assertSee(__('Mailgun'))
        ->assertSee(__('Sending domain'));
});

test('mailgun settings can be updated', function () {
    $user = User::factory()->onboarded()->create();
    SmtpSetting::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(Mailgun::class)
        ->set('mailgun_api_key', 'key-new-api-key-12345')
        ->set('mailgun_domain', 'mg.example.com')
        ->set('sender_name', 'Updated Sender')
        ->set('sender_email', 'updated@example.com')
        ->call('updateMailgunSettings')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('smtp_settings', [
        'user_id' => $user->id,
        'domain' => 'mg.example.com',
        'sender_name' => 'Updated Sender',
        'sender_email' => 'updated@example.com',
    ]);
});

test('mailgun settings validation rejects invalid data', function (string $field, mixed $value) {
    $user = User::factory()->onboarded()->create();
    SmtpSetting::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(Mailgun::class)
        ->set($field, $value)
        ->call('updateMailgunSettings')
        ->assertHasErrors($field);
})->with([
    'api key too short' => ['mailgun_api_key', 'short'],
    'domain too short' => ['mailgun_domain', 'ab'],
    'sender name too short' => ['sender_name', 'a'],
    'sender email invalid' => ['sender_email', 'not-an-email'],
]);
