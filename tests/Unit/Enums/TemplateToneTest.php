<?php

declare(strict_types=1);

use App\Enums\TemplateTone;

test('template tone has expected cases', function () {
    expect(TemplateTone::cases())->toHaveCount(3)
        ->and(TemplateTone::Professional->value)->toBe('professional')
        ->and(TemplateTone::Casual->value)->toBe('casual')
        ->and(TemplateTone::Elegant->value)->toBe('elegant');
});

test('template tone returns a label', function () {
    expect(TemplateTone::Professional->label())->toBe(__('Professional'))
        ->and(TemplateTone::Casual->label())->toBe(__('Casual'))
        ->and(TemplateTone::Elegant->label())->toBe(__('Elegant'));
});
