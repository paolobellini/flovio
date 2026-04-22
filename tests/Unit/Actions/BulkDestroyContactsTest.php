<?php

declare(strict_types=1);

use App\Actions\BulkDestroyContactsAction;
use App\Models\Contact;

test('bulk destroy deletes the given contacts', function () {
    $contacts = Contact::factory()->count(3)->create();

    $count = resolve(BulkDestroyContactsAction::class)->handle(
        $contacts->pluck('id')->all()
    );

    expect($count)->toBe(3)
        ->and(Contact::query()->count())->toBe(0);
});

test('bulk destroy is limited to 10 contacts', function () {
    $contacts = Contact::factory()->count(12)->create();

    $count = resolve(BulkDestroyContactsAction::class)->handle(
        $contacts->pluck('id')->all()
    );

    expect($count)->toBe(10)
        ->and(Contact::query()->count())->toBe(2);
});
