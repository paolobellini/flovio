<?php

declare(strict_types=1);

use App\DTOs\ContactImportAnalysis;
use App\Events\ContactImportCompleted;
use App\Listeners\LogContactImportCompleted;
use App\Models\ContactImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

test('completed listener writes info entry to imports channel', function () {
    $channel = Mockery::mock();
    $channel->shouldReceive('info')
        ->once()
        ->withArgs(function (string $message, array $context): bool {
            return $message === 'Contact import completed'
                && $context['total'] === 5
                && $context['valid'] === 4
                && $context['invalid'] === 1
                && $context['duplicates'] === 0
                && $context['duration_seconds'] === 2.5;
        });

    Log::shouldReceive('channel')->with('imports')->once()->andReturn($channel);

    $import = ContactImport::factory()->create();
    $analysis = new ContactImportAnalysis(total: 5, valid: 4, invalid: 1, duplicates: 0, validRows: Collection::make());

    resolve(LogContactImportCompleted::class)->handle(
        new ContactImportCompleted($import, $analysis, 2.5),
    );
});
