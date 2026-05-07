<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ContactImport;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class ContactImportFailed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ContactImport $contactImport,
        public Throwable $exception,
        public float $durationSeconds,
    ) {}
}
