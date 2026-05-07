<?php

declare(strict_types=1);

namespace App\Events;

use App\DTOs\ContactImportAnalysis;
use App\Models\ContactImport;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ContactImportCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ContactImport $contactImport,
        public ContactImportAnalysis $analysis,
        public float $durationSeconds,
    ) {}
}
