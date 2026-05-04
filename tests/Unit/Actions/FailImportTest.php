<?php

declare(strict_types=1);

use App\Actions\FailImportAction;
use App\Enums\ImportStatus;
use App\Events\ContactImportFailed;
use App\Models\ContactImport;
use Illuminate\Support\Facades\Event;

test('mark contact import failed stores error and dispatches event', function () {
    Event::fake([ContactImportFailed::class]);

    $import = ContactImport::factory()->create([
        'status' => ImportStatus::Processing,
    ]);

    $exception = new RuntimeException('boom');

    resolve(FailImportAction::class)->handle($import, $exception, 0.5);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Failed)
        ->and($import->errors)->toBe([['message' => 'boom']]);

    Event::assertDispatched(
        ContactImportFailed::class,
        fn (ContactImportFailed $e): bool => $e->contactImport->is($import)
            && $e->exception === $exception
            && $e->durationSeconds === 0.5,
    );
});
