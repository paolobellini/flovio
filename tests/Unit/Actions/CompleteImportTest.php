<?php

declare(strict_types=1);

use App\Actions\CompleteImportAction;
use App\DTOs\ContactImportAnalysis;
use App\Enums\ImportStatus;
use App\Events\ContactImportCompleted;
use App\Models\ContactImport;
use Illuminate\Support\Facades\Event;

test('mark contact import completed persists analysis stats and dispatches event', function () {
    Event::fake([ContactImportCompleted::class]);

    $import = ContactImport::factory()->create([
        'status' => ImportStatus::Processing,
        'completed_at' => null,
    ]);

    $analysis = new ContactImportAnalysis(
        total: 10,
        valid: 7,
        invalid: 2,
        duplicates: 1,
        validRows: [],
    );

    resolve(CompleteImportAction::class)->handle($import, $analysis, 1.234);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Completed)
        ->and($import->processed_rows)->toBe(10)
        ->and($import->created_count)->toBe(7)
        ->and($import->skipped_count)->toBe(1)
        ->and($import->failed_count)->toBe(2)
        ->and($import->completed_at)->not->toBeNull();

    Event::assertDispatched(
        ContactImportCompleted::class,
        fn (ContactImportCompleted $e): bool => $e->contactImport->is($import)
            && $e->durationSeconds === 1.234
            && $e->analysis === $analysis,
    );
});
