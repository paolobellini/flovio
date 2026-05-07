<?php

declare(strict_types=1);

use App\Actions\StoreTemplateAction;
use App\Enums\TemplateLayout;
use App\Models\Template;

test('store template creates a new template', function () {
    $template = resolve(StoreTemplateAction::class)->handle([
        'name' => 'Newsletter',
        'description' => 'Weekly newsletter',
        'primary_color' => '#7B2D42',
        'layout' => 'single',
        'tone' => 'professional',
    ]);

    expect($template)->toBeInstanceOf(Template::class)
        ->and($template->name)->toBe('Newsletter')
        ->and($template->layout)->toBe(TemplateLayout::Single);
});
