<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AiSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AiSetting>
 */
final class AiSettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'chat_model' => 'gemini-2.5-flash',
            'image_model' => 'gemini-2.5-flash-image',
            'content_model' => 'gemini-2.5-flash',
            'openai_api_key' => 'sk-'.fake()->sha256(),
            'anthropic_api_key' => 'sk-ant-'.fake()->sha256(),
            'google_api_key' => 'AIza'.fake()->sha256(),
        ];
    }
}
