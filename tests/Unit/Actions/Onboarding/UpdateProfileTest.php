<?php

declare(strict_types=1);

use App\Actions\Onboarding\UpdateProfileAction;
use App\Models\User;

test('update profile updates user attributes', function () {
    $user = User::factory()->create(['name' => 'Old Name']);
    $action = new UpdateProfileAction();

    $action->handle($user, [
        'name' => 'Paolo Bellini',
        'company_name' => 'Acme Inc',
        'timezone' => 'Europe/Rome',
    ]);

    $user->refresh();

    expect($user->name)->toBe('Paolo Bellini');
    expect($user->company_name)->toBe('Acme Inc');
    expect($user->timezone)->toBe('Europe/Rome');
});
