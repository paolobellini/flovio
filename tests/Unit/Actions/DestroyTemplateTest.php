<?php

declare(strict_types=1);

use App\Actions\DestroyTemplateAction;
use App\Models\Template;

test('destroy template deletes the template', function () {
    $template = Template::factory()->create();

    resolve(DestroyTemplateAction::class)->handle($template);

    expect(Template::query()->count())->toBe(0);
});
