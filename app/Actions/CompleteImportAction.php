<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\ContactImportAnalysis;
use App\Enums\ImportStatus;
use App\Events\ContactImportCompleted;
use App\Models\ContactImport;

final readonly class CompleteImportAction
{
    public function handle(ContactImport $import, ContactImportAnalysis $analysis, float $durationSeconds): void
    {
        $import->update([
            'status' => ImportStatus::Completed,
            'processed_rows' => $analysis->total,
            'created_count' => $analysis->valid,
            'skipped_count' => $analysis->duplicates,
            'failed_count' => $analysis->invalid,
            'completed_at' => now(),
        ]);

        ContactImportCompleted::dispatch($import, $analysis, $durationSeconds);
    }
}
