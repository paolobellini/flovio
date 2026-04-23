<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Template;

final readonly class StoreTemplateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Template
    {
        return Template::query()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'primary_color' => $data['primary_color'],
            'layout' => $data['layout'],
            'tone' => $data['tone'],
        ]);
    }
}
