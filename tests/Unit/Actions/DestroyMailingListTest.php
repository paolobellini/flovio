<?php

declare(strict_types=1);

use App\Actions\DestroyMailingListAction;
use App\Models\Contact;
use App\Models\MailingList;

test('destroy mailing list deletes the list and detaches contacts', function () {
    $list = MailingList::factory()->create();
    $contacts = Contact::factory()->count(3)->create();
    $list->contacts()->attach($contacts);

    resolve(DestroyMailingListAction::class)->handle($list);

    expect(MailingList::query()->count())->toBe(0)
        ->and(Contact::query()->count())->toBe(3);
});
