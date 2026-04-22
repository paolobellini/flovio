<?php

declare(strict_types=1);

namespace App\Actions\Onboarding;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class CompleteOnboardingAction
{
    public function __construct(
        private UpdateProfileAction $updateProfile,
        private StoreSmtpSettingAction $storeSmtpSetting,
        private StoreAiSettingAction $storeAiSetting,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data): void {
            $this->updateProfile->handle($user, $data);
            $this->storeSmtpSetting->handle($user, $data);
            $this->storeAiSetting->handle($user);

            $user->update(['onboarded_at' => now()]);
        });
    }
}
