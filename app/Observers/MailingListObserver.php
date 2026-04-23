<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\MailingList;
use Illuminate\Support\Facades\Cache;

final class MailingListObserver
{
    public function created(MailingList $mailingList): void
    {
        $this->flush();
    }

    public function updated(MailingList $mailingList): void
    {
        $this->flush();
    }

    public function deleted(MailingList $mailingList): void
    {
        $this->flush();
    }

    private function flush(): void
    {
        Cache::tags(['mailing_lists'])->flush();
    }
}
