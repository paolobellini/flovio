<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Contact;

final readonly class UpdateContactAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Contact $contact, array $data): Contact
    {
        $contact->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        return $contact;
    }
}
