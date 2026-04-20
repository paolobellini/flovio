<?php

declare(strict_types=1);

use App\Http\Requests\Onboarding\ProfileRequest;

test('profile request is authorized', function () {
    expect((new ProfileRequest())->authorize())->toBeTrue();
});

test('profile request accepts valid data', function () {
    $validator = validator([
        'name' => 'Paolo Bellini',
        'company_name' => 'Acme Inc',
        'timezone' => 'Europe/Rome',
    ], (new ProfileRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

test('profile request rejects invalid data', function (array $data, string $field) {
    $validator = validator($data, (new ProfileRequest())->rules());

    expect($validator->errors()->has($field))->toBeTrue();
})->with([
    'short name' => [['name' => 'A', 'timezone' => 'UTC'], 'name'],
    'bad timezone' => [['name' => 'Paolo', 'timezone' => 'Fake/Zone'], 'timezone'],
]);
