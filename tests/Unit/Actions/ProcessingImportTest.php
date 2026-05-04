<?php

declare(strict_types=1);

use App\Actions\ProcessingImportAction;
use App\Enums\ImportStatus;
use App\Events\ContactImportProcessing;
use App\Models\ContactImport;
use Illuminate\Support\Facades\Event;

test('mark contact import processing flips status and dispatches event', function () {
    Event::fake([ContactImportProcessing::class]);

    $import = ContactImport::factory()->create([
        'status' => ImportStatus::Pending,
    ]);

    resolve(ProcessingImportAction::class)->handle($import);

    expect($import->refresh()->status)->toBe(ImportStatus::Processing);

    Event::assertDispatched(
        ContactImportProcessing::class,
        fn (ContactImportProcessing $e): bool => $e->contactImport->is($import),
    );
});
