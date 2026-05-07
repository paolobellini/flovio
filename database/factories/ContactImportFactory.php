<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ImportStatus;
use App\Models\ContactImport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactImport>
 */
final class ContactImportFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'file_name' => fake()->word().'.csv',
            'file_path' => 'imports/'.fake()->word().'.csv',
            'status' => ImportStatus::Pending,
            'delimiter' => ',',
            'name_column' => 'name',
            'email_column' => 'email',
            'total_rows' => fake()->numberBetween(10, 1000),
            'processed_rows' => 0,
            'created_count' => 0,
            'skipped_count' => 0,
            'failed_count' => 0,
        ];
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ImportStatus::Processing,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ImportStatus::Completed,
            'processed_rows' => $attributes['total_rows'],
            'created_count' => $attributes['total_rows'],
            'completed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ImportStatus::Failed,
            'completed_at' => now(),
        ]);
    }
}
