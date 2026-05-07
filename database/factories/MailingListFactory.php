<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MailingList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MailingList>
 */
final class MailingListFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['envelope', 'megaphone', 'star', 'sparkles', 'calendar-days']),
            'color' => fake()->randomElement(['wine', 'blue', 'amber', 'green', 'purple', 'orange']),
            'is_ai_generated' => false,
        ];
    }

    public function aiGenerated(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_ai_generated' => true,
        ]);
    }
}
