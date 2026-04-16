<?php

declare(strict_types=1);

namespace App\Livewire\Settings\TwoFactor;

use Exception;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Component;

final class RecoveryCodes extends Component
{
    /** @var array<int, string> */
    #[Locked]
    public array $recoveryCodes = [];

    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $generateNewRecoveryCodes($user);

        $this->loadRecoveryCodes();
    }

    private function loadRecoveryCodes(): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $codes = $user->two_factor_recovery_codes;

        if ($user->hasEnabledTwoFactorAuthentication() && is_string($codes)) {
            try {
                $decrypted = decrypt($codes);

                if (is_string($decrypted)) {
                    $decoded = json_decode($decrypted, true);
                    $this->recoveryCodes = is_array($decoded) ? array_values(array_filter($decoded, 'is_string')) : [];
                }
            } catch (Exception) {
                $this->addError('recoveryCodes', 'Failed to load recovery codes');

                $this->recoveryCodes = [];
            }
        }
    }
}
