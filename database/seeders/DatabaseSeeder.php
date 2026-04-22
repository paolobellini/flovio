<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AiSetting;
use App\Models\Contact;
use App\Models\SmtpSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Paolo Bellini',
            'email' => 'paolo@bellini.one',
            'onboarded_at' => now(),
        ]);

        SmtpSetting::factory()->create([
            'user_id' => $user->id,
        ]);
        AiSetting::factory()->create([
            'user_id' => $user->id,
        ]);

        Contact::factory(50)->create();
        Contact::factory(10)->unsubscribed()->create();
    }
}
