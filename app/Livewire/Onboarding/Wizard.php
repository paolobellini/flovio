<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding;

use App\Actions\Onboarding\CompleteOnboardingAction;
use App\Http\Requests\Onboarding\ProfileRequest;
use App\Http\Requests\Onboarding\SmtpSettingRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Onboarding')]
#[Layout('layouts.auth.split')]
final class Wizard extends Component
{
    public int $currentStep = 1;

    public int $totalSteps = 3;

    public string $name = '';

    public string $company_name = '';

    public string $timezone = '';

    public string $mailgun_api_key = '';

    public string $mailgun_domain = '';

    public string $sender_name = '';

    public string $sender_email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name ?? '';
        /** @var string $timezone */
        $timezone = config('app.timezone', 'UTC');
        $this->timezone = $timezone;
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        $this->currentStep = min($this->currentStep + 1, $this->totalSteps);
    }

    public function previousStep(): void
    {
        $this->currentStep = max($this->currentStep - 1, 1);
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function complete(
        #[CurrentUser] User $user,
        CompleteOnboardingAction $action,
    ): void {
        $action->handle($user, [
            'name' => $this->name,
            'company_name' => $this->company_name,
            'timezone' => $this->timezone,
            'mailgun_api_key' => $this->mailgun_api_key,
            'mailgun_domain' => $this->mailgun_domain,
            'sender_name' => $this->sender_name,
            'sender_email' => $this->sender_email,
        ]);

        $this->redirect(route('dashboard'));
    }

    private function validateCurrentStep(): void
    {
        match ($this->currentStep) {
            1 => $this->validate((new ProfileRequest())->rules()),
            2 => $this->validate((new SmtpSettingRequest())->rules()),
            default => null,
        };
    }
}
