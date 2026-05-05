<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ContactImportFailed as ContactImportFailedEvent;
use Illuminate\Support\Facades\Log;

final class ContactImportFailed
{
    public function handle(ContactImportFailedEvent $event): void
    {
        Log::channel('imports')->error('Contact import failed', [
            'contact_import_id' => $event->contactImport->id,
            'user_id' => $event->contactImport->user_id,
            'file_name' => $event->contactImport->file_name,
            'duration_seconds' => round($event->durationSeconds, 3),
            'exception' => $event->exception::class,
            'message' => $event->exception->getMessage(),
        ]);
    }
}
