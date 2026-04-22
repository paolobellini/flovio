<?php

declare(strict_types=1);

use App\Actions\StoreContactAction;
use App\Enums\ContactStatus;
use App\Models\Contact;

test('store contact creates a new subscribed contact', function () {
    $contact = resolve(StoreContactAction::class)->handle([
        'name' => 'Marco Rossi',
        'email' => 'marco@example.com',
    ]);

    expect($contact)->toBeInstanceOf(Contact::class)
        ->and($contact->name)->toBe('Marco Rossi')
        ->and($contact->status)->toBe(ContactStatus::Subscribed);
});
