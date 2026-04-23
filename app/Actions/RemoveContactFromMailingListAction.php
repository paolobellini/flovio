<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Contact;
use App\Models\MailingList;
use Illuminate\Support\Facades\Cache;

final readonly class RemoveContactFromMailingListAction
{
    public function handle(Contact $contact, MailingList $list): void
    {
        $contact->mailingLists()->detach($list);

        Cache::tags(['mailing_lists'])->flush();
    }
}
