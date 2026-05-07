<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Models\MailingList;

test('mailing list has expected attributes', function () {
    $list = MailingList::factory()->create();
    $list->refresh();

    expect($list->toArray())->toHaveKeys([
        'id',
        'name',
        'description',
        'icon',
        'color',
        'is_ai_generated',
        'created_at',
        'updated_at',
    ]);
});

test('mailing list has many contacts', function () {
    $list = MailingList::factory()->create();
    $contacts = Contact::factory()->count(3)->create();

    $list->contacts()->attach($contacts);

    expect($list->contacts)->toHaveCount(3)
        ->each->toBeInstanceOf(Contact::class);
});

test('contact belongs to many mailing lists', function () {
    $contact = Contact::factory()->create();
    $lists = MailingList::factory()->count(2)->create();

    $contact->mailingLists()->attach($lists);

    expect($contact->mailingLists)->toHaveCount(2)
        ->each->toBeInstanceOf(MailingList::class);
});
