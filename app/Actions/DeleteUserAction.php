<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class DeleteUserAction
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->aiSetting->delete();
            $user->smtpSetting->delete();
            $user->delete();
        });
    }
}
