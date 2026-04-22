<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ContactStatus;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
final class ContactFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'name' => fake()->name(),
            'status' => ContactStatus::Subscribed,
            'subscribed_at' => now(),
        ];
    }

    public function unsubscribed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContactStatus::Unsubscribed,
            'unsubscribed_at' => now(),
        ]);
    }
}
