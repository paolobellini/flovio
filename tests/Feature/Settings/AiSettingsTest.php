<?php

declare(strict_types=1);

use App\Livewire\Settings\AiSettings;
use App\Models\AiSetting;
use App\Models\User;
use Livewire\Livewire;

test('ai settings page can be rendered', function () {
    $user = User::factory()->onboarded()->create();
    AiSetting::factory()->for($user)->create();

    $this->actingAs($user)
        ->get(route('ai.edit'))
        ->assertOk()
        ->assertSee(__('AI'))
        ->assertSee(__('Chat model'));
});

test('ai settings can be updated', function () {
    $user = User::factory()->onboarded()->create();
    AiSetting::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(AiSettings::class)
        ->set('chat_model', 'gpt-4o')
        ->set('image_model', 'dall-e-3')
        ->set('content_model', 'claude-sonnet-4-20250514')
        ->set('openai_api_key', 'sk-test-key-12345')
        ->set('anthropic_api_key', 'sk-ant-test-key-67890')
        ->set('google_api_key', '')
        ->call('updateAiSettings')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('ai_settings', [
        'user_id' => $user->id,
        'chat_model' => 'gpt-4o',
        'image_model' => 'dall-e-3',
        'content_model' => 'claude-sonnet-4-20250514',
    ]);
});

test('ai settings validation rejects invalid data', function (string $field, mixed $value) {
    $user = User::factory()->onboarded()->create();
    AiSetting::factory()->for($user)->create();

    $this->actingAs($user);

    Livewire::test(AiSettings::class)
        ->set($field, $value)
        ->call('updateAiSettings')
        ->assertHasErrors($field);
})->with([
    'chat model empty' => ['chat_model', ''],
    'image model empty' => ['image_model', ''],
    'content model empty' => ['content_model', ''],
]);
