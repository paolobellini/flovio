<?php

declare(strict_types=1);

use App\Actions\StoreMailingListAction;
use App\Models\MailingList;

test('store mailing list creates a new list', function () {
    $list = resolve(StoreMailingListAction::class)->handle([
        'name' => 'Newsletter',
        'description' => 'Weekly newsletter subscribers',
        'icon' => 'envelope',
        'color' => 'wine',
    ]);

    expect($list)->toBeInstanceOf(MailingList::class)
        ->and($list->name)->toBe('Newsletter')
        ->and($list->icon)->toBe('envelope')
        ->and($list->color)->toBe('wine');
});
