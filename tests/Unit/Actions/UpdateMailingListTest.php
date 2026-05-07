<?php

declare(strict_types=1);

use App\Actions\UpdateMailingListAction;
use App\Models\MailingList;

test('update mailing list updates the list details', function () {
    $list = MailingList::factory()->create(['name' => 'Old Name']);

    resolve(UpdateMailingListAction::class)->handle($list, [
        'name' => 'New Name',
        'description' => 'Updated description',
        'icon' => 'star',
        'color' => 'blue',
    ]);

    expect($list->fresh())
        ->name->toBe('New Name')
        ->and($list->fresh()->description)->toBe('Updated description');
});
