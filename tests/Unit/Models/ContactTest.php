<?php

declare(strict_types=1);

use App\Enums\ContactStatus;
use App\Models\Contact;
use App\Models\User;

test('contact has expected attributes', function () {
    $contact = Contact::factory()->create();
    $contact->refresh();

    expect($contact->toArray())->toHaveKeys([
        'id',
        'user_id',
        'email',
        'name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'created_at',
        'updated_at',
    ]);
});

test('contact belongs to a user', function () {
    $contact = Contact::factory()->create();

    expect($contact->user)->toBeInstanceOf(User::class);
});

test('contact status is cast to enum', function () {
    $contact = Contact::factory()->create();

    expect($contact->status)->toBeInstanceOf(ContactStatus::class)
        ->and($contact->status)->toBe(ContactStatus::Subscribed);
});
