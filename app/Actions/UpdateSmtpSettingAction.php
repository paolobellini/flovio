<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\SmtpSetting;
use App\Models\User;

final readonly class UpdateSmtpSettingAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): SmtpSetting
    {
        $smtpSetting = $user->smtpSetting;

        $smtpSetting->update([
            'api_key' => $data['mailgun_api_key'],
            'domain' => $data['mailgun_domain'],
            'sender_name' => $data['sender_name'],
            'sender_email' => $data['sender_email'],
        ]);

        return $smtpSetting;
    }
}
