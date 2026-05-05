<?php

declare(strict_types=1);

use App\Enums\ImportStatus;
use App\Jobs\ProcessContactImportJob;
use App\Models\Contact;
use App\Models\ContactImport;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

test('job reads csv with duckdb and updates import record', function () {
    $disk = Storage::fake('local');

    $user = User::factory()->onboarded()->create();

    $csvContent = "Name;Email\nMarco Rossi;marco@example.com\nLucia Bianchi;lucia@example.com\n";

    $path = 'imports/contacts_test.csv';
    $disk->put($path, $csvContent);

    $import = ContactImport::factory()->create([
        'user_id' => $user->id,
        'file_path' => $path,
        'delimiter' => ';',
        'name_column' => 'Name',
        'email_column' => 'Email',
        'total_rows' => 2,
        'status' => ImportStatus::Pending,
    ]);

    ProcessContactImportJob::dispatchSync($import);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Completed)
        ->and($import->processed_rows)->toBe(2)
        ->and($import->created_count)->toBe(2)
        ->and($import->skipped_count)->toBe(0)
        ->and($import->failed_count)->toBe(0)
        ->and($import->completed_at)->not->toBeNull();

    expect(Contact::query()->where('email', 'marco@example.com')->exists())->toBeTrue()
        ->and(Contact::query()->where('email', 'lucia@example.com')->exists())->toBeTrue();
});

test('job marks rows with invalid email as failed', function () {
    $disk = Storage::fake('local');

    $user = User::factory()->onboarded()->create();

    $csvContent = "Name;Email\nMarco Rossi;not-an-email\nLucia Bianchi;lucia@example.com\n";

    $path = 'imports/contacts_invalid.csv';
    $disk->put($path, $csvContent);

    $import = ContactImport::factory()->create([
        'user_id' => $user->id,
        'file_path' => $path,
        'delimiter' => ';',
        'name_column' => 'Name',
        'email_column' => 'Email',
        'total_rows' => 2,
        'status' => ImportStatus::Pending,
    ]);

    ProcessContactImportJob::dispatchSync($import);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Completed)
        ->and($import->processed_rows)->toBe(2)
        ->and($import->created_count)->toBe(1)
        ->and($import->failed_count)->toBe(1);
});

test('job filters rows with empty name or email', function () {
    $disk = Storage::fake('local');

    $user = User::factory()->onboarded()->create();

    $csvContent = "Name;Email\n;marco@example.com\nLucia Bianchi;\nValid Name;valid@example.com\n";

    $path = 'imports/contacts_empty.csv';
    $disk->put($path, $csvContent);

    $import = ContactImport::factory()->create([
        'user_id' => $user->id,
        'file_path' => $path,
        'delimiter' => ';',
        'name_column' => 'Name',
        'email_column' => 'Email',
        'total_rows' => 3,
        'status' => ImportStatus::Pending,
    ]);

    ProcessContactImportJob::dispatchSync($import);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Completed)
        ->and($import->processed_rows)->toBe(3)
        ->and($import->created_count)->toBe(1)
        ->and($import->failed_count)->toBe(2);
});

test('job deduplicates rows by email keeping first occurrence', function () {
    $disk = Storage::fake('local');

    $user = User::factory()->onboarded()->create();

    $csvContent = "Name;Email\nMarco Rossi;marco@example.com\nMarco Duplicate;marco@example.com\nLucia Bianchi;lucia@example.com\n";

    $path = 'imports/contacts_dupes.csv';
    $disk->put($path, $csvContent);

    $import = ContactImport::factory()->create([
        'user_id' => $user->id,
        'file_path' => $path,
        'delimiter' => ';',
        'name_column' => 'Name',
        'email_column' => 'Email',
        'total_rows' => 3,
        'status' => ImportStatus::Pending,
    ]);

    ProcessContactImportJob::dispatchSync($import);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Completed)
        ->and($import->processed_rows)->toBe(3)
        ->and($import->created_count)->toBe(2)
        ->and($import->skipped_count)->toBe(1)
        ->and($import->failed_count)->toBe(0);
});

test('job marks import as failed on exception', function () {
    $user = User::factory()->onboarded()->create();

    $import = ContactImport::factory()->create([
        'user_id' => $user->id,
        'file_path' => 'imports/nonexistent.csv',
        'delimiter' => ',',
        'name_column' => 'Name',
        'email_column' => 'Email',
        'total_rows' => 1,
        'status' => ImportStatus::Pending,
    ]);

    try {
        ProcessContactImportJob::dispatchSync($import);
    } catch (Throwable) {
        //
    }

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Failed)
        ->and($import->errors)->not->toBeNull();
});

test('job handles comma-delimited csv', function () {
    $disk = Storage::fake('local');

    $user = User::factory()->onboarded()->create();

    $csvContent = "full_name,email_address\nPaolo Bellini,paolo@example.com\n";

    $path = 'imports/contacts_comma.csv';
    $disk->put($path, $csvContent);

    $import = ContactImport::factory()->create([
        'user_id' => $user->id,
        'file_path' => $path,
        'delimiter' => ',',
        'name_column' => 'full_name',
        'email_column' => 'email_address',
        'total_rows' => 1,
        'status' => ImportStatus::Pending,
    ]);

    ProcessContactImportJob::dispatchSync($import);

    $import->refresh();

    expect($import->status)->toBe(ImportStatus::Completed)
        ->and($import->processed_rows)->toBe(1)
        ->and($import->created_count)->toBe(1);
});
