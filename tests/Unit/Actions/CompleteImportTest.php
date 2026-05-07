<?php

declare(strict_types=1);

use App\Actions\CompleteImportAction;
use App\DTOs\ContactImportAnalysis;
use App\Enums\ImportStatus;
use App\Events\ContactImportCompleted;
use App\Models\Contact;
use App\Models\ContactImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

test('mark contact import completed inserts contacts, persists stats, and dispatches event', function () {
    Event::fake([ContactImportCompleted::class]);

    $import = ContactImport::factory()->create([
        'status' => ImportStatus::Processing,
        'completed_at' => null,
    ]);

    $validRows = Collection::make([
        ['name' => 'Marco Rossi', 'email' => 'marco@example.com'],
        ['name' => 'Lucia Bianchi', 'email' => 'lucia@example.com'],
    ]);

    $analysis = new ContactImportAnalysis(
        total: 10,
        valid: 2,
        invalid: 2,
        duplicates: 1,
        validRows: $validRows,
    );

    resolve(CompleteImportAction::class)->handle($import, $analysis, 1.234);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Completed)
        ->and($import->processed_rows)->toBe(10)
        ->and($import->created_count)->toBe(2)
        ->and($import->skipped_count)->toBe(1)
        ->and($import->failed_count)->toBe(2)
        ->and($import->completed_at)->not->toBeNull();

    expect(Contact::query()->whereIn('email', ['marco@example.com', 'lucia@example.com'])->count())->toBe(2);

    Event::assertDispatched(fn (ContactImportCompleted $e): bool => $e->contactImport->is($import)
        && $e->durationSeconds === 1.234
        && $e->analysis === $analysis);
});

test('mark contact import completed counts existing emails as skipped', function () {
    Event::fake([ContactImportCompleted::class]);

    Contact::factory()->create(['email' => 'existing@example.com']);

    $import = ContactImport::factory()->create([
        'status' => ImportStatus::Processing,
        'completed_at' => null,
    ]);

    $analysis = new ContactImportAnalysis(
        total: 3,
        valid: 2,
        invalid: 0,
        duplicates: 0,
        validRows: Collection::make([
            ['name' => 'New', 'email' => 'new@example.com'],
            ['name' => 'Existing', 'email' => 'existing@example.com'],
        ]),
    );

    resolve(CompleteImportAction::class)->handle($import, $analysis, 0.5);

    $import->refresh();

    expect($import->created_count)->toBe(1)
        ->and($import->skipped_count)->toBe(1)
        ->and($import->failed_count)->toBe(0);
});
