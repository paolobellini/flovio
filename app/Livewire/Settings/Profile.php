<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profile settings')]
final class Profile extends Component
{
    use ProfileValidationRules;

    public string $name = '';

    public string $email = '';

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $this->validate($this->profileRules((int) $user->id));

        $user->fill(['name' => $this->name, 'email' => $this->email]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __('A new verification link has been sent to your email address.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        $user = Auth::user();

        return $user !== null && ! $user->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->hasVerifiedEmail();
    }
}
