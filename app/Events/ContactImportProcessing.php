<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ContactImport;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ContactImportProcessing
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ContactImport $contactImport,
    ) {}
}
