<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\CompleteImportAction;
use App\Actions\FailImportAction;
use App\Actions\ProcessingImportAction;
use App\Models\ContactImport;
use App\Services\ContactImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessContactImportJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ContactImport $contactImport,
    ) {}

    public function handle(
        ContactImportService $service,
        ProcessingImportAction $processing,
        CompleteImportAction $complete,
        FailImportAction $fail,
    ): void {
        $startedAt = microtime(true);

        $processing->handle($this->contactImport);

        try {
            $analysis = $service->analyze($this->contactImport);

            $complete->handle($this->contactImport, $analysis, microtime(true) - $startedAt);
        } catch (\Throwable $e) {
            $fail->handle($this->contactImport, $e, microtime(true) - $startedAt);

            throw $e;
        }
    }
}
