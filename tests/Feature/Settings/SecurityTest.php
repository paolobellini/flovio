<?php

declare(strict_types=1);

use App\Livewire\Settings\Security;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Features;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);
});

test('security settings page can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertOk()
        ->assertSee('Two-factor authentication')
        ->assertSee('Enable 2FA');
});

test('security settings page requires password confirmation when enabled', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('security.edit'));

    $response->assertRedirect(route('password.confirm'));
});

test('security settings page renders without two factor when feature is disabled', function () {
    config(['fortify.features' => []]);

    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertOk()
        ->assertSee('Update password')
        ->assertDontSee('Two-factor authentication');
});

test('two factor authentication disabled when confirmation abandoned between requests', function () {
    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_secret' => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => null,
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test(Security::class);

    $component->assertSet('twoFactorEnabled', false);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'two_factor_secret' => null,
        'two_factor_recovery_codes' => null,
    ]);
});

test('password can be updated', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $response = Livewire::test(Security::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword');

    $response->assertHasNoErrors();

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $response = Livewire::test(Security::class)
        ->set('current_password', 'wrong-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword');

    $response->assertHasErrors(['current_password']);
});

test('password confirmation must match', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $response = Livewire::test(Security::class)
        ->set('current_password', 'password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'wrong-confirmation')
        ->call('updatePassword');

    $response->assertHasErrors(['password']);
});

test('two factor authentication can be enabled', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable');

    $component->assertSet('showModal', true);
    $component->assertSet('qrCodeSvg', fn ($value) => str_contains($value, '<svg'));
    $component->assertSet('manualSetupKey', fn ($value) => $value !== '');

    expect($user->refresh()->two_factor_secret)->not->toBeNull();
});

test('two factor authentication shows verification step when confirmation is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable')
        ->call('showVerificationIfNecessary');

    $component->assertSet('showVerificationStep', true);
    $component->assertSet('showModal', true);
});

test('two factor authentication closes modal when confirmation is not required', function () {
    Features::twoFactorAuthentication([
        'confirm' => false,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable')
        ->call('showVerificationIfNecessary');

    $component->assertSet('showVerificationStep', false);
    $component->assertSet('showModal', false);
});

test('two factor authentication can be confirmed with valid code', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable');

    $secret = decrypt($user->refresh()->two_factor_secret);

    $validCode = resolve(Google2FA::class)->getCurrentOtp($secret);

    $component
        ->set('code', $validCode)
        ->call('confirmTwoFactor');

    $component->assertSet('twoFactorEnabled', true);
    $component->assertSet('showModal', false);

    expect($user->refresh()->two_factor_confirmed_at)->not->toBeNull();
});

test('two factor authentication cannot be confirmed with invalid code', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable')
        ->set('code', '000000')
        ->call('confirmTwoFactor');

    $component->assertSet('twoFactorEnabled', false);

    expect($user->refresh()->two_factor_confirmed_at)->toBeNull();
});

test('two factor authentication verification can be reset', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable')
        ->call('showVerificationIfNecessary');

    $component->assertSet('showVerificationStep', true);

    $component->call('resetVerification');

    $component->assertSet('showVerificationStep', false);
    $component->assertSet('code', '');
});

test('two factor authentication can be disabled', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class);

    $component->assertSet('twoFactorEnabled', true);

    $component->call('disable');

    $component->assertSet('twoFactorEnabled', false);

    expect($user->refresh()->two_factor_secret)->toBeNull();
});

test('modal can be closed and state is reset', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable');

    $component->assertSet('showModal', true);

    $component->call('closeModal');

    $component->assertSet('showModal', false);
    $component->assertSet('qrCodeSvg', '');
    $component->assertSet('manualSetupKey', '');
    $component->assertSet('code', '');
    $component->assertSet('showVerificationStep', false);
});

test('modal config returns correct data when two factor is enabled', function () {
    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class);

    expect($component->get('modalConfig')['title'])->toBe('Two-factor authentication enabled');
});

test('modal config returns correct data for verification step', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable')
        ->call('showVerificationIfNecessary');

    expect($component->get('modalConfig')['title'])->toBe('Verify authentication code');
});

test('modal config returns correct data for initial setup', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable');

    expect($component->get('modalConfig')['title'])->toBe('Enable two-factor authentication');
});

test('enabling two factor handles corrupted secret gracefully', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(Security::class)
        ->call('enable');

    $user->forceFill(['two_factor_secret' => 'not-encrypted'])->save();

    $component->call('closeModal');
    $component->call('enable');

    $component->assertHasErrors('setupData');
});
