<?php

declare(strict_types=1);

use App\Enums\ContactStatus;

test('contact status has expected cases', function () {
    expect(ContactStatus::cases())->toHaveCount(2)
        ->and(ContactStatus::Subscribed->value)->toBe('subscribed')
        ->and(ContactStatus::Unsubscribed->value)->toBe('unsubscribed');
});

test('contact status returns a label', function () {
    expect(ContactStatus::Subscribed->label())->toBe(__('Subscribed'))
        ->and(ContactStatus::Unsubscribed->label())->toBe(__('Unsubscribed'));
});

test('contact status returns a color', function () {
    expect(ContactStatus::Subscribed->color())->toBe('green')
        ->and(ContactStatus::Unsubscribed->color())->toBe('zinc');
});
