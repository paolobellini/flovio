<?php

declare(strict_types=1);

use App\DTOs\ContactImportAnalysis;
use App\Events\ContactImportCompleted as ContactImportCompletedEvent;
use App\Listeners\ContactImportCompleted;
use App\Models\ContactImport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

test('completed listener writes info entry and flushes contacts cache', function () {
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

    $repository = Mockery::mock();
    $repository->shouldReceive('flush')->once();

    Cache::shouldReceive('tags')->with(['contacts'])->once()->andReturn($repository);

    $import = ContactImport::factory()->create();
    $analysis = new ContactImportAnalysis(total: 5, valid: 4, invalid: 1, duplicates: 0, validRows: Collection::make());

    resolve(ContactImportCompleted::class)->handle(
        new ContactImportCompletedEvent($import, $analysis, 2.5),
    );
});
