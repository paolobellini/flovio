<?php

declare(strict_types=1);

use App\Actions\AddContactToMailingListAction;
use App\Models\Contact;
use App\Models\MailingList;

test('add contact to mailing lists attaches the lists', function () {
    $contact = Contact::factory()->create();
    $lists = MailingList::factory()->count(2)->create();

    resolve(AddContactToMailingListAction::class)->handle(
        $contact,
        $lists->pluck('id')->all(),
    );

    expect($contact->mailingLists)->toHaveCount(2);
});
