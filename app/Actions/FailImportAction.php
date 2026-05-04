<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ImportStatus;
use App\Events\ContactImportFailed;
use App\Models\ContactImport;
use Throwable;

final readonly class FailImportAction
{
    public function handle(ContactImport $import, Throwable $exception, float $durationSeconds): void
    {
        $import->update([
            'status' => ImportStatus::Failed,
            'errors' => [['message' => $exception->getMessage()]],
        ]);

        ContactImportFailed::dispatch($import, $exception, $durationSeconds);
    }
}
