<?php

declare(strict_types=1);

use App\Enums\TemplateLayout;

test('template layout has expected cases', function () {
    expect(TemplateLayout::cases())->toHaveCount(3)
        ->and(TemplateLayout::Single->value)->toBe('single')
        ->and(TemplateLayout::TwoColumn->value)->toBe('two-column')
        ->and(TemplateLayout::Hero->value)->toBe('hero');
});

test('template layout returns a label', function () {
    expect(TemplateLayout::Single->label())->toBe(__('Single column'))
        ->and(TemplateLayout::TwoColumn->label())->toBe(__('Two columns'))
        ->and(TemplateLayout::Hero->label())->toBe(__('Hero image'));
});
