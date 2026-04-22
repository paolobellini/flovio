<?php

declare(strict_types=1);

use App\Actions\Onboarding\StoreAiSettingAction;
use App\Models\AiSetting;
use App\Models\User;

test('store ai setting creates a new setting with defaults for user', function () {
    $user = User::factory()->create();

    $setting = resolve(StoreAiSettingAction::class)->handle($user);

    expect($setting)->toBeInstanceOf(AiSetting::class)
        ->and($setting->user_id)->toBe($user->id)
        ->and($setting->chat_model)->toBe('gemini-2.5-flash')
        ->and($setting->image_model)->toBe('gemini-2.5-flash-image');
});
