<?php

declare(strict_types=1);

namespace App\Actions\Onboarding;

use App\Models\SmtpSetting;
use App\Models\User;

final readonly class StoreSmtpSettingAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): SmtpSetting
    {
        return $user->smtpSetting()->create([
            'api_key' => $data['mailgun_api_key'],
            'domain' => $data['mailgun_domain'],
            'sender_name' => $data['sender_name'],
            'sender_email' => $data['sender_email'],
        ]);
    }
}
