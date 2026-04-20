<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SmtpSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SmtpSetting>
 */
final class SmtpSettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'api_key' => 'key-'.fake()->sha256(),
            'domain' => 'mg.'.fake()->domainName(),
            'sender_name' => fake()->company(),
            'sender_email' => fake()->companyEmail(),
        ];
    }
}
