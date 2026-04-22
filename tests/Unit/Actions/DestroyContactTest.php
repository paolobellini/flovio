<?php

declare(strict_types=1);

use App\Actions\DestroyContactAction;
use App\Models\Contact;

test('destroy contact deletes the contact', function () {
    $contact = Contact::factory()->create();

    resolve(DestroyContactAction::class)->handle($contact);

    expect(Contact::query()->count())->toBe(0);
});
