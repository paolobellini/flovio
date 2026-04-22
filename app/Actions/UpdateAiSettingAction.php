<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\AiSetting;
use App\Models\User;

final readonly class UpdateAiSettingAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): AiSetting
    {
        $aiSetting = $user->aiSetting;

        $aiSetting->update([
            'chat_model' => $data['chat_model'],
            'image_model' => $data['image_model'],
            'content_model' => $data['content_model'],
            'openai_api_key' => $data['openai_api_key'],
            'anthropic_api_key' => $data['anthropic_api_key'],
            'google_api_key' => $data['google_api_key'],
        ]);

        return $aiSetting;
    }
}
