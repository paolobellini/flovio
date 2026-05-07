<?php

declare(strict_types=1);

use App\Actions\RemoveMemberFromMailingListAction;
use App\Models\Contact;
use App\Models\MailingList;

test('remove member detaches contact from the mailing list', function () {
    $list = MailingList::factory()->create();
    $contact = Contact::factory()->create();
    $list->contacts()->attach($contact);

    resolve(RemoveMemberFromMailingListAction::class)->handle($list, $contact);

    expect($list->contacts()->count())->toBe(0)
        ->and(Contact::query()->count())->toBe(1);
});
