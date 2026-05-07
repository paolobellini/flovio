<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\MailingList;

final readonly class DestroyMailingListAction
{
    public function handle(MailingList $list): void
    {
        $list->contacts()->detach();
        $list->delete();
    }
}
