<?php

declare(strict_types=1);

use App\Actions\UpdateSmtpSettingAction;
use App\Models\SmtpSetting;
use App\Models\User;

test('update smtp setting updates existing setting for user', function () {
    $user = User::factory()->create();
    SmtpSetting::factory()->for($user)->create();

    resolve(UpdateSmtpSettingAction::class)->handle($user, [
        'mailgun_api_key' => 'key-new-api-key-67890',
        'mailgun_domain' => 'mg.new-domain.com',
        'sender_name' => 'New Sender',
        'sender_email' => 'new@example.com',
    ]);

    expect($user->smtpSetting->fresh())
        ->domain->toBe('mg.new-domain.com')
        ->and($user->smtpSetting->fresh()->api_key)->toBe('key-new-api-key-67890');
});
