<?php

declare(strict_types=1);

use App\Actions\BulkStoreContactsAction;
use App\Models\Contact;
use Illuminate\Support\Collection;

test('bulk store inserts new contacts and returns inserted count', function () {
    $rows = Collection::make([
        ['name' => 'Marco Rossi', 'email' => 'marco@example.com'],
        ['name' => 'Lucia Bianchi', 'email' => 'lucia@example.com'],
    ]);

    $inserted = resolve(BulkStoreContactsAction::class)->handle($rows);

    expect($inserted)->toBe(2)
        ->and(Contact::query()->whereIn('email', ['marco@example.com', 'lucia@example.com'])->count())->toBe(2);
});

test('bulk store skips existing emails via insertOrIgnore', function () {
    Contact::factory()->create(['email' => 'existing@example.com']);

    $rows = Collection::make([
        ['name' => 'New One', 'email' => 'new@example.com'],
        ['name' => 'Existing One', 'email' => 'existing@example.com'],
    ]);

    $inserted = resolve(BulkStoreContactsAction::class)->handle($rows);

    expect($inserted)->toBe(1);
});
