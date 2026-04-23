<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\MailingList;

final readonly class StoreMailingListAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): MailingList
    {
        return MailingList::query()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'],
            'color' => $data['color'],
        ]);
    }
}
