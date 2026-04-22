<?php

declare(strict_types=1);

use App\Actions\UpdateAiSettingAction;
use App\Models\AiSetting;
use App\Models\User;

test('update ai setting updates existing setting for user', function () {
    $user = User::factory()->create();
    AiSetting::factory()->for($user)->create();

    resolve(UpdateAiSettingAction::class)->handle($user, [
        'chat_model' => 'gpt-4o',
        'image_model' => 'dall-e-3',
        'content_model' => 'claude-sonnet-4-20250514',
        'openai_api_key' => 'sk-new-key-123',
        'anthropic_api_key' => 'sk-ant-new-key-456',
        'google_api_key' => 'AIza-new-key-789',
    ]);

    expect($user->aiSetting->fresh())
        ->chat_model->toBe('gpt-4o')
        ->and($user->aiSetting->fresh()->openai_api_key)->toBe('sk-new-key-123');
});
