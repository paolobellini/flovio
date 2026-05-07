<?php

declare(strict_types=1);

use App\Enums\ContactStatus;
use App\Models\Contact;

test('contact has expected attributes', function () {
    $contact = Contact::factory()->create();
    $contact->refresh();

    expect($contact->toArray())->toHaveKeys([
        'id',
        'email',
        'name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'created_at',
        'updated_at',
    ]);
});

test('contact status is cast to enum', function () {
    $contact = Contact::factory()->create();

    expect($contact->status)->toBeInstanceOf(ContactStatus::class)
        ->and($contact->status)->toBe(ContactStatus::Subscribed);
});
