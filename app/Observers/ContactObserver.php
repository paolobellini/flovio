<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Contact;
use Illuminate\Support\Facades\Cache;

final class ContactObserver
{
    public function created(Contact $contact): void
    {
        $this->flush();
    }

    public function updated(Contact $contact): void
    {
        $this->flush();
    }

    public function deleted(Contact $contact): void
    {
        $this->flush();
    }

    private function flush(): void
    {
        Cache::tags(['contacts'])->flush();
        Cache::tags(['mailing_lists'])->flush();
    }
}
