<?php

declare(strict_types=1);

use App\Actions\UpdateContactAction;
use App\Models\Contact;

test('update contact updates the contact details', function () {
    $contact = Contact::factory()->create(['name' => 'Marco Rossi']);

    resolve(UpdateContactAction::class)->handle($contact, [
        'name' => 'Marco Bianchi',
        'email' => 'marco.bianchi@example.com',
    ]);

    expect($contact->fresh())
        ->name->toBe('Marco Bianchi')
        ->and($contact->fresh()->email)->toBe('marco.bianchi@example.com');
});
