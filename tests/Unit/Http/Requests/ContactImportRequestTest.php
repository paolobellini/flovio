<?php

declare(strict_types=1);

use App\Http\Requests\ContactImportRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

test('valid data passes validation', function () {
    $file = UploadedFile::fake()->create('contacts.csv', 100, 'text/csv');

    $validator = Validator::make([
        'file' => $file,
        'nameColumn' => 'Name',
        'emailColumn' => 'email',
        'delimiter' => ';',
        'totalRows' => 10,
    ], (new ContactImportRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

test('invalid data fails validation', function (array $data, string $field) {
    $validator = Validator::make($data, (new ContactImportRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with([
    'missing file' => [['nameColumn' => 'Name', 'emailColumn' => 'email', 'delimiter' => ',', 'totalRows' => 10], 'file'],
    'missing name column' => [['file' => UploadedFile::fake()->create('c.csv', 10, 'text/csv'), 'emailColumn' => 'email', 'delimiter' => ',', 'totalRows' => 10], 'nameColumn'],
    'missing email column' => [['file' => UploadedFile::fake()->create('c.csv', 10, 'text/csv'), 'nameColumn' => 'Name', 'delimiter' => ',', 'totalRows' => 10], 'emailColumn'],
    'zero rows' => [['file' => UploadedFile::fake()->create('c.csv', 10, 'text/csv'), 'nameColumn' => 'Name', 'emailColumn' => 'email', 'delimiter' => ',', 'totalRows' => 0], 'totalRows'],
    'file too large' => [['file' => UploadedFile::fake()->create('c.csv', 6000, 'text/csv'), 'nameColumn' => 'Name', 'emailColumn' => 'email', 'delimiter' => ',', 'totalRows' => 10], 'file'],
]);
