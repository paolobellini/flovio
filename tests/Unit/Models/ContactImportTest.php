<?php

declare(strict_types=1);

use App\Enums\ImportStatus;
use App\Models\ContactImport;
use App\Models\User;

test('contact import has expected attributes', function () {
    $import = ContactImport::factory()->create();
    $import->refresh();

    expect($import->toArray())->toHaveKeys([
        'id',
        'user_id',
        'file_name',
        'file_path',
        'status',
        'delimiter',
        'name_column',
        'email_column',
        'total_rows',
        'processed_rows',
        'created_count',
        'skipped_count',
        'failed_count',
        'errors',
        'completed_at',
        'created_at',
        'updated_at',
    ]);
});

test('contact import status is cast to enum', function () {
    $import = ContactImport::factory()->create();

    expect($import->status)->toBeInstanceOf(ImportStatus::class)
        ->and($import->status)->toBe(ImportStatus::Pending);
});

test('contact import belongs to a user', function () {
    $import = ContactImport::factory()->create();

    expect($import->user)->toBeInstanceOf(User::class);
});
