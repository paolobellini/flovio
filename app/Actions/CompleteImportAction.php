<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\ContactImportAnalysis;
use App\Enums\ImportStatus;
use App\Events\ContactImportCompleted;
use App\Models\ContactImport;

final readonly class CompleteImportAction
{
    public function __construct(
        private BulkStoreContactsAction $bulkStoreContacts,
    ) {}

    public function handle(ContactImport $import, ContactImportAnalysis $analysis, float $durationSeconds): void
    {
        $inserted = $this->bulkStoreContacts->handle($analysis->validRows);

        $existingSkipped = max($analysis->valid - $inserted, 0);

        $import->update([
            'status' => ImportStatus::Completed,
            'processed_rows' => $analysis->total,
            'created_count' => $inserted,
            'skipped_count' => $analysis->duplicates + $existingSkipped,
            'failed_count' => $analysis->invalid,
            'completed_at' => now(),
        ]);

        ContactImportCompleted::dispatch($import, $analysis, $durationSeconds);
    }
}
