<?php

declare(strict_types=1);

namespace App\Livewire\Contacts;

use App\Models\Contact;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Contact')]
final class Show extends Component
{
    public Contact $contact;

    public function mount(Contact $contact): void
    {
        $this->contact = $contact;
    }
}
