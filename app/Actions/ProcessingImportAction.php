<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ImportStatus;
use App\Events\ContactImportProcessing;
use App\Models\ContactImport;

final readonly class ProcessingImportAction
{
    public function handle(ContactImport $import): void
    {
        $import->update(['status' => ImportStatus::Processing]);

        ContactImportProcessing::dispatch($import);
    }
}
