<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\MailingList;

final readonly class AddMembersToMailingListAction
{
    /**
     * @param  array<int, int>  $contactIds
     */
    public function handle(MailingList $list, array $contactIds): void
    {
        $list->contacts()->syncWithoutDetaching($contactIds);
    }
}
