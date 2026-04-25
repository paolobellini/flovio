<?php

declare(strict_types=1);

use App\Enums\ImportStatus;

test('import status has expected cases', function () {
    expect(ImportStatus::cases())->toHaveCount(4)
        ->and(ImportStatus::Pending->value)->toBe('pending')
        ->and(ImportStatus::Processing->value)->toBe('processing')
        ->and(ImportStatus::Completed->value)->toBe('completed')
        ->and(ImportStatus::Failed->value)->toBe('failed');
});

test('import status returns a label', function () {
    expect(ImportStatus::Pending->label())->toBe(__('Pending'))
        ->and(ImportStatus::Processing->label())->toBe(__('Processing'))
        ->and(ImportStatus::Completed->label())->toBe(__('Completed'))
        ->and(ImportStatus::Failed->label())->toBe(__('Failed'));
});

test('import status returns a color', function () {
    expect(ImportStatus::Pending->color())->toBe('zinc')
        ->and(ImportStatus::Processing->color())->toBe('sky')
        ->and(ImportStatus::Completed->color())->toBe('green')
        ->and(ImportStatus::Failed->color())->toBe('red');
});
