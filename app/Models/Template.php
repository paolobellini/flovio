<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TemplateLayout;
use App\Enums\TemplateTone;
use Database\Factories\TemplateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property string $name
 * @property ?string $description
 * @property ?string $body
 * @property string $primary_color
 * @property TemplateLayout $layout
 * @property TemplateTone $tone
 * @property ?string $last_prompt
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
final class Template extends Model
{
    /** @use HasFactory<TemplateFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'description' => 'string',
            'body' => 'string',
            'primary_color' => 'string',
            'layout' => TemplateLayout::class,
            'tone' => TemplateTone::class,
            'last_prompt' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
