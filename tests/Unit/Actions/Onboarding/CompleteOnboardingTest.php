<?php

declare(strict_types=1);

use App\Actions\Onboarding\CompleteOnboardingAction;
use App\Models\User;

test('complete onboarding updates profile, creates smtp setting, and marks user as onboarded', function () {
    $user = User::factory()->create();

    $action = resolve(CompleteOnboardingAction::class);

    $action->handle($user, [
        'name' => 'Paolo Bellini',
        'company_name' => 'Acme Inc',
        'timezone' => 'Europe/Rome',
        'mailgun_api_key' => 'key-abc12345678',
        'mailgun_domain' => 'mg.example.com',
        'sender_name' => 'Acme',
        'sender_email' => 'hello@example.com',
    ]);

    $user->refresh();

    expect($user->name)->toBe('Paolo Bellini');
    expect($user->company_name)->toBe('Acme Inc');
    expect($user->timezone)->toBe('Europe/Rome');
    expect($user->isOnboarded())->toBeTrue();
    expect($user->smtpSetting)->not->toBeNull();
    expect($user->smtpSetting->domain)->toBe('mg.example.com');
});
