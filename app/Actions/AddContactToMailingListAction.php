<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Contact;
use Illuminate\Support\Facades\Cache;

final readonly class AddContactToMailingListAction
{
    /**
     * @param  array<int, int>  $listIds
     */
    public function handle(Contact $contact, array $listIds): void
    {
        $contact->mailingLists()->syncWithoutDetaching($listIds);

        Cache::tags(['mailing_lists'])->flush();
    }
}
