<?php

declare(strict_types=1);

namespace App\Actions\Onboarding;

use App\Models\User;

final readonly class UpdateProfileAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'],
            'company_name' => $data['company_name'] ?? null,
            'timezone' => $data['timezone'],
        ]);
    }
}
