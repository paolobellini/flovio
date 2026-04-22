<?php

declare(strict_types=1);

use App\Models\AiSetting;
use App\Models\User;

test('ai setting has expected attributes', function () {
    $setting = AiSetting::factory()->create();
    $setting->refresh();

    expect($setting->toArray())->toHaveKeys([
        'id',
        'user_id',
        'chat_model',
        'image_model',
        'content_model',
        'created_at',
        'updated_at',
    ]);
});

test('ai setting belongs to a user', function () {
    $setting = AiSetting::factory()->create();

    expect($setting->user)->toBeInstanceOf(User::class);
});

test('api keys are hidden from array', function () {
    $setting = AiSetting::factory()->create();

    expect($setting->toArray())
        ->not->toHaveKey('openai_api_key')
        ->and($setting->toArray())->not->toHaveKey('anthropic_api_key')
        ->and($setting->toArray())->not->toHaveKey('google_api_key');
});

test('api keys are encrypted', function () {
    $setting = AiSetting::factory()->create(['openai_api_key' => 'sk-test123']);

    $setting->refresh();

    expect($setting->openai_api_key)->toBe('sk-test123')
        ->and($setting->getRawOriginal('openai_api_key'))->not->toBe('sk-test123');
});
