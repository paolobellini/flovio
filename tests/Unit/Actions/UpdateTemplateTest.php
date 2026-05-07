<?php

declare(strict_types=1);

use App\Actions\UpdateTemplateAction;
use App\Models\Template;

test('update template updates the template details', function () {
    $template = Template::factory()->create(['name' => 'Old Name']);

    resolve(UpdateTemplateAction::class)->handle($template, [
        'name' => 'New Name',
        'description' => 'Updated description',
        'primary_color' => '#FF0000',
        'layout' => 'hero',
        'tone' => 'casual',
    ]);

    expect($template->fresh())
        ->name->toBe('New Name')
        ->and($template->fresh()->primary_color)->toBe('#FF0000');
});
