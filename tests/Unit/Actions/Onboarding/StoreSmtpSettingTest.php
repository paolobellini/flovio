<?php

declare(strict_types=1);

use App\Actions\Onboarding\StoreSmtpSettingAction;
use App\Models\SmtpSetting;
use App\Models\User;

test('store smtp setting creates a new setting for user', function () {
    $user = User::factory()->create();
    $action = new StoreSmtpSettingAction();

    $setting = $action->handle($user, [
        'mailgun_api_key' => 'key-abc12345678',
        'mailgun_domain' => 'mg.example.com',
        'sender_name' => 'Acme',
        'sender_email' => 'hello@example.com',
    ]);

    expect($setting)->toBeInstanceOf(SmtpSetting::class);
    expect($setting->domain)->toBe('mg.example.com');
    expect($setting->sender_name)->toBe('Acme');
    expect($setting->sender_email)->toBe('hello@example.com');
    expect($setting->api_key)->toBe('key-abc12345678');
    expect($setting->user_id)->toBe($user->id);
});
