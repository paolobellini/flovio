<?php

declare(strict_types=1);

namespace App\Livewire\Onboarding;

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
        $this->name = Auth::user()?->name ?? '';
        $this->timezone = config('app.timezone', 'UTC');
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

    public function complete(): void
    {
        $this->redirect(route('dashboard'));
    }

    private function validateCurrentStep(): void
    {
        match ($this->currentStep) {
            1 => $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'company_name' => ['nullable', 'string', 'max:255'],
                'timezone' => ['required', 'string', 'timezone:all'],
            ]),
            2 => $this->validate([
                'mailgun_api_key' => ['required', 'string'],
                'mailgun_domain' => ['required', 'string', 'max:255'],
                'sender_name' => ['required', 'string', 'max:255'],
                'sender_email' => ['required', 'email', 'max:255'],
            ]),
            default => null,
        };
    }
}
