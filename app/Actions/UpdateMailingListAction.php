<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\MailingList;

final readonly class UpdateMailingListAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(MailingList $list, array $data): MailingList
    {
        $list->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'],
            'color' => $data['color'],
        ]);

        return $list;
    }
}
