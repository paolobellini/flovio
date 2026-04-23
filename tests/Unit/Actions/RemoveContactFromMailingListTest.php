<?php

declare(strict_types=1);

use App\Actions\RemoveContactFromMailingListAction;
use App\Models\Contact;
use App\Models\MailingList;

test('remove contact from mailing list detaches the list', function () {
    $contact = Contact::factory()->create();
    $list = MailingList::factory()->create();
    $contact->mailingLists()->attach($list);

    resolve(RemoveContactFromMailingListAction::class)->handle($contact, $list);

    expect($contact->mailingLists()->count())->toBe(0)
        ->and(MailingList::query()->count())->toBe(1);
});
