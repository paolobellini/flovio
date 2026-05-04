<?php

declare(strict_types=1);

use App\Events\ContactImportFailed;
use App\Listeners\LogContactImportFailed;
use App\Models\ContactImport;
use Illuminate\Support\Facades\Log;

test('failed listener writes error entry to imports channel', function () {
    $channel = Mockery::mock();
    $channel->shouldReceive('error')
        ->once()
        ->withArgs(function (string $message, array $context): bool {
            return $message === 'Contact import failed'
                && $context['exception'] === RuntimeException::class
                && $context['message'] === 'boom'
                && $context['duration_seconds'] === 0.75;
        });

    Log::shouldReceive('channel')->with('imports')->once()->andReturn($channel);

    $import = ContactImport::factory()->create();

    resolve(LogContactImportFailed::class)->handle(
        new ContactImportFailed($import, new RuntimeException('boom'), 0.75),
    );
});
