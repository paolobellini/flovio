<?php

declare(strict_types=1);

use App\Livewire\Settings\TwoFactor\RecoveryCodes;
use App\Models\User;
use Laravel\Fortify\Features;
use Livewire\Livewire;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());
});

test('recovery codes are loaded for user with two factor enabled', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user);

    $component = Livewire::test(RecoveryCodes::class);

    expect($component->get('recoveryCodes'))->toBeArray()->not->toBeEmpty();
});

test('recovery codes are empty for user without two factor', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(RecoveryCodes::class);

    expect($component->get('recoveryCodes'))->toBeEmpty();
});

test('recovery codes can be regenerated', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user);

    $component = Livewire::test(RecoveryCodes::class);

    $originalCodes = $component->get('recoveryCodes');

    $component->call('regenerateRecoveryCodes');

    expect($component->get('recoveryCodes'))
        ->toBeArray()
        ->not->toBeEmpty()
        ->not->toBe($originalCodes);
});

test('recovery codes handles corrupted data gracefully', function () {
    $user = User::factory()->withTwoFactor()->create();

    $user->forceFill([
        'two_factor_recovery_codes' => 'not-encrypted-data',
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test(RecoveryCodes::class);

    expect($component->get('recoveryCodes'))->toBeEmpty();
    $component->assertHasErrors('recoveryCodes');
});
