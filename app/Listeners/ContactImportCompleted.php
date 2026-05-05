<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactImportCompleted as ContactImportCompletedEvent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

final class ContactImportCompleted
{
    public function handle(ContactImportCompletedEvent $event): void
    {
        Log::channel('imports')->info('Contact import completed', [
            'contact_import_id' => $event->contactImport->id,
            'user_id' => $event->contactImport->user_id,
            'file_name' => $event->contactImport->file_name,
            'duration_seconds' => round($event->durationSeconds, 3),
            'total' => $event->analysis->total,
            'valid' => $event->analysis->valid,
            'invalid' => $event->analysis->invalid,
            'duplicates' => $event->analysis->duplicates,
        ]);

        Cache::tags(['contacts'])->flush();
    }
}
