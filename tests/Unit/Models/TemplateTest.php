<?php

declare(strict_types=1);

use App\Enums\TemplateLayout;
use App\Enums\TemplateTone;
use App\Models\Template;

test('template has expected attributes', function () {
    $template = Template::factory()->create();
    $template->refresh();

    expect($template->toArray())->toHaveKeys([
        'id',
        'name',
        'description',
        'body',
        'primary_color',
        'layout',
        'tone',
        'last_prompt',
        'created_at',
        'updated_at',
    ]);
});

test('template layout is cast to enum', function () {
    $template = Template::factory()->create();

    expect($template->layout)->toBeInstanceOf(TemplateLayout::class)
        ->and($template->layout)->toBe(TemplateLayout::Single);
});

test('template tone is cast to enum', function () {
    $template = Template::factory()->create();

    expect($template->tone)->toBeInstanceOf(TemplateTone::class)
        ->and($template->tone)->toBe(TemplateTone::Professional);
});
