<?php

declare(strict_types=1);

use App\Actions\AddMembersToMailingListAction;
use App\Models\Contact;
use App\Models\MailingList;

test('add members attaches contacts to the mailing list', function () {
    $list = MailingList::factory()->create();
    $contacts = Contact::factory()->count(3)->create();

    resolve(AddMembersToMailingListAction::class)->handle(
        $list,
        $contacts->pluck('id')->all(),
    );

    expect($list->contacts)->toHaveCount(3);
});

test('add members does not duplicate existing contacts', function () {
    $list = MailingList::factory()->create();
    $contact = Contact::factory()->create();
    $list->contacts()->attach($contact);

    resolve(AddMembersToMailingListAction::class)->handle(
        $list,
        [$contact->id],
    );

    expect($list->contacts()->count())->toBe(1);
});
