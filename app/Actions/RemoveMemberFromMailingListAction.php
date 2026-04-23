<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Contact;
use App\Models\MailingList;

final readonly class RemoveMemberFromMailingListAction
{
    public function handle(MailingList $list, Contact $contact): void
    {
        $list->contacts()->detach($contact);
    }
}
