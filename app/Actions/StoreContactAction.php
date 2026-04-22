<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\ContactStatus;
use App\Models\Contact;

final readonly class StoreContactAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Contact
    {
        return Contact::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => ContactStatus::Subscribed,
            'subscribed_at' => now(),
        ]);
    }
}
