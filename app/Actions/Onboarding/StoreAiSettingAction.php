<?php

declare(strict_types=1);

namespace App\Actions\Onboarding;

use App\Models\AiSetting;
use App\Models\User;

final readonly class StoreAiSettingAction
{
    public function handle(User $user): AiSetting
    {
        return $user->aiSetting()->create();
    }
}
