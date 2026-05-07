<?php

declare(strict_types=1);

use App\Actions\StoreContactImportAction;
use App\Enums\ImportStatus;
use App\Models\ContactImport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('store contact import creates record and stores file', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->createWithContent('contacts.csv', "Name;Email\nMarco;marco@example.com\n");

    $import = resolve(StoreContactImportAction::class)->handle($user, $file, [
        'delimiter' => ';',
        'nameColumn' => 'Name',
        'emailColumn' => 'Email',
        'totalRows' => 1,
    ]);

    expect($import)->toBeInstanceOf(ContactImport::class)
        ->and($import->user_id)->toBe($user->id)
        ->and($import->file_name)->toBe('contacts.csv')
        ->and($import->delimiter)->toBe(';')
        ->and($import->name_column)->toBe('Name')
        ->and($import->email_column)->toBe('Email')
        ->and($import->total_rows)->toBe(1)
        ->and($import->refresh()->status)->toBe(ImportStatus::Pending);

    Storage::disk('local')->assertExists($import->file_path);
});
