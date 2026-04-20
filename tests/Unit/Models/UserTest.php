<?php

declare(strict_types=1);

use App\Models\SmtpSetting;
use App\Models\User;

test('user has expected attributes', function () {
    $user = User::factory()->create();
    $user->refresh();

    expect($user->toArray())->toHaveKeys([
        'id',
        'name',
        'email',
        'email_verified_at',
        'company_name',
        'timezone',
        'onboarded_at',
        'two_factor_confirmed_at',
        'created_at',
        'updated_at',
    ]);
});

test('user has smtp setting relationship', function () {
    $user = User::factory()->create();

    SmtpSetting::factory()->for($user)->create();

    expect($user->smtpSetting)->toBeInstanceOf(SmtpSetting::class);
});

