<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Contact;

final readonly class DestroyContactAction
{
    public function handle(Contact $contact): void
    {
        $contact->delete();
    }
}
