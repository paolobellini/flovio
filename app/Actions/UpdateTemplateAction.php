<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Template;

final readonly class UpdateTemplateAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Template $template, array $data): Template
    {
        $template->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'primary_color' => $data['primary_color'],
            'layout' => $data['layout'],
            'tone' => $data['tone'],
        ]);

        return $template;
    }
}
