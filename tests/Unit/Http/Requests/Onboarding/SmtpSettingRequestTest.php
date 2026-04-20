<?php

declare(strict_types=1);

use App\Http\Requests\Onboarding\SmtpSettingRequest;

test('smtp setting request is authorized', function () {
    expect((new SmtpSettingRequest())->authorize())->toBeTrue();
});

test('smtp setting request accepts valid data', function () {
    $validator = validator([
        'mailgun_api_key' => 'key-abc12345678',
        'mailgun_domain' => 'mg.example.com',
        'sender_name' => 'Acme Inc',
        'sender_email' => 'hello@example.com',
    ], (new SmtpSettingRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

test('smtp setting request rejects invalid data', function (array $override, string $field) {
    $valid = ['mailgun_api_key' => 'key-abc12345678', 'mailgun_domain' => 'mg.example.com', 'sender_name' => 'Acme', 'sender_email' => 'a@b.com'];

    $validator = validator([...$valid, ...$override], (new SmtpSettingRequest())->rules());

    expect($validator->errors()->has($field))->toBeTrue();
})->with([
    'short api key' => [['mailgun_api_key' => 'short'], 'mailgun_api_key'],
    'empty domain' => [['mailgun_domain' => ''], 'mailgun_domain'],
    'bad sender email' => [['sender_email' => 'not-an-email'], 'sender_email'],
]);
