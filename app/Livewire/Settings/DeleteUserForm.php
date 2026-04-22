<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Actions\DeleteUserAction;
use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Livewire\Component;

final class DeleteUserForm extends Component
{
    use PasswordValidationRules;

    public string $password = '';

    public function deleteUser(#[CurrentUser] User $user, Logout $logout, DeleteUserAction $action): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        $logout();
        $action->handle($user);

        $this->redirect('/', navigate: true);
    }
}
