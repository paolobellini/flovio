<?php

declare(strict_types=1);

use App\Models\ContactImport;
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

test('user has many contact imports', function () {
    $user = User::factory()->create();
    ContactImport::factory()->count(3)->for($user)->create();

    expect($user->contactImports)->toHaveCount(3)
        ->each->toBeInstanceOf(ContactImport::class);
});
