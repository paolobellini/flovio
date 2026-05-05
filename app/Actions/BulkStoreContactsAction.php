<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ContactStatus;
use App\Models\Contact;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final readonly class BulkStoreContactsAction
{
    private const int CHUNK_SIZE = 500;

    /**
     * @param  Collection<int, array{name: string, email: string}>  $rows
     */
    public function handle(Collection $rows): int
    {
        $now = Carbon::now();

        return $rows
            ->chunk(self::CHUNK_SIZE)
            ->sum(fn (Collection $chunk): int => Contact::query()->insertOrIgnore(
                $chunk->map(fn (array $row): array => [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'status' => ContactStatus::Subscribed->value,
                    'subscribed_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all(),
            ));
    }
}
