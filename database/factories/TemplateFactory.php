<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TemplateLayout;
use App\Enums\TemplateTone;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Template>
 */
final class TemplateFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'primary_color' => '#7B2D42',
            'layout' => TemplateLayout::Single,
            'tone' => TemplateTone::Professional,
        ];
    }
}
