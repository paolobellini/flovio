<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\MailingList;
use Illuminate\Support\Facades\Cache;

final readonly class AddMembersToMailingListAction
{
    /**
     * @param  array<int, int>  $contactIds
     */
    public function handle(MailingList $list, array $contactIds): void
    {
        $list->contacts()->syncWithoutDetaching($contactIds);

        Cache::tags(['mailing_lists'])->flush();
    }
}
