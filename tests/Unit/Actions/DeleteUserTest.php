<?php

declare(strict_types=1);

use App\Actions\DeleteUserAction;
use App\Models\AiSetting;
use App\Models\SmtpSetting;
use App\Models\User;

test('delete user removes user and all related settings', function () {
    $user = User::factory()->create();
    SmtpSetting::factory()->for($user)->create();
    AiSetting::factory()->for($user)->create();

    resolve(DeleteUserAction::class)->handle($user);

    $this->assertDatabaseMissing('users', ['id' => $user->id])
        ->assertDatabaseMissing('smtp_settings', ['user_id' => $user->id])
        ->assertDatabaseMissing('ai_settings', ['user_id' => $user->id]);
});
