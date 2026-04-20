<?php

declare(strict_types=1);

use App\Models\SmtpSetting;
use App\Models\User;

test('smtp setting has expected attributes', function () {
    $setting = SmtpSetting::factory()->create();
    $setting->refresh();

    expect($setting->toArray())->toHaveKeys([
        'id',
        'user_id',
        'domain',
        'sender_name',
        'sender_email',
        'created_at',
        'updated_at',
    ]);
});

test('smtp setting belongs to a user', function () {
    $setting = SmtpSetting::factory()->create();

    expect($setting->user)->toBeInstanceOf(User::class);
});

test('api key is hidden from array', function () {
    $setting = SmtpSetting::factory()->create();

    expect($setting->toArray())->not->toHaveKey('api_key');
});

test('api key is encrypted', function () {
    $setting = SmtpSetting::factory()->create(['api_key' => 'key-test123']);

    $setting->refresh();

    expect($setting->api_key)->toBe('key-test123');

    $raw = $setting->getRawOriginal('api_key');
    expect($raw)->not->toBe('key-test123');
});
