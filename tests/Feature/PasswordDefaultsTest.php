<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use Illuminate\Validation\Rules\Password;

test('production password defaults enforce strong rules', function () {
    app()->detectEnvironment(fn () => 'production');

    (new AppServiceProvider(app()))->boot();

    $rules = Password::defaults();

    $validator = validator(['password' => 'weak'], ['password' => $rules]);

    expect($validator->fails())->toBeTrue();
});
