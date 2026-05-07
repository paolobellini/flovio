<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Contact;

final readonly class BulkDestroyContactsAction
{
    private const int MAX_CONTACTS = 10;

    /**
     * @param  array<int, int>  $ids
     */
    public function handle(array $ids): int
    {
        $contacts = Contact::query()
            ->whereIn('id', array_slice($ids, 0, self::MAX_CONTACTS))
            ->get();

        $contacts->each->delete();

        return $contacts->count();
    }
}
