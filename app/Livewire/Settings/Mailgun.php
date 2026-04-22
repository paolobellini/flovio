<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Actions\UpdateSmtpSettingAction;
use App\Http\Requests\Onboarding\SmtpSettingRequest;
use App\Models\User;
use Flux\Flux;
use Illuminate\Container\Attributes\CurrentUser;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Mailgun settings')]
final class Mailgun extends Component
{
    public string $mailgun_api_key = '';

    public string $mailgun_domain = '';

    public string $sender_name = '';

    public string $sender_email = '';

    public function mount(#[CurrentUser] User $user): void
    {
        $smtpSetting = $user->smtpSetting;

        $this->mailgun_api_key = $smtpSetting->api_key;
        $this->mailgun_domain = $smtpSetting->domain;
        $this->sender_name = $smtpSetting->sender_name;
        $this->sender_email = $smtpSetting->sender_email;
    }

    public function updateMailgunSettings(#[CurrentUser] User $user, UpdateSmtpSettingAction $action): void
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validate(new SmtpSettingRequest()->rules());

        $action->handle($user, $validated);

        Flux::toast(variant: 'success', text: __('Mailgun settings updated.'));
    }
}
