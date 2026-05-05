<?php

declare(strict_types=1);

use App\Jobs\ProcessContactImportJob;
use App\Livewire\Contacts\Import;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('import stores file, creates record, and dispatches processing job', function () {
    Bus::fake();
    Storage::fake('local');

    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    $file = UploadedFile::fake()->createWithContent(
        'contacts.csv',
        "Name;Email\nMarco Rossi;marco@example.com\nLucia Bianchi;lucia@example.com\n",
    );

    Livewire::test(Import::class)
        ->set('file', $file)
        ->assertSet('delimiter', ';')
        ->assertSet('nameColumn', 'Name')
        ->assertSet('emailColumn', 'Email')
        ->assertSet('totalRows', 2)
        ->call('import')
        ->assertHasNoErrors()
        ->assertSet('step', 3);

    $this->assertDatabaseHas('contact_imports', [
        'user_id' => $user->id,
        'file_name' => 'contacts.csv',
        'delimiter' => ';',
        'name_column' => 'Name',
        'email_column' => 'Email',
        'total_rows' => 2,
        'status' => 'pending',
    ]);

    Bus::assertDispatched(
        ProcessContactImportJob::class,
        fn (ProcessContactImportJob $job): bool => $job->contactImport->user_id === $user->id
            && $job->contactImport->file_name === 'contacts.csv',
    );
});

test('import validates required columns', function () {
    $user = User::factory()->onboarded()->create();

    $this->actingAs($user);

    $file = UploadedFile::fake()->createWithContent(
        'contacts.csv',
        "Name;Email\nMarco;marco@example.com\n",
    );

    Livewire::test(Import::class)
        ->set('file', $file)
        ->set('nameColumn', '')
        ->set('emailColumn', '')
        ->call('import')
        ->assertHasErrors(['nameColumn', 'emailColumn']);
});
