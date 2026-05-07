<?php

declare(strict_types=1);

namespace App\Livewire\Contacts;

use App\Actions\AddContactToMailingListAction;
use App\Actions\RemoveContactFromMailingListAction;
use App\Models\Contact;
use App\Models\MailingList;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Contact')]
final class Show extends Component
{
    public Contact $contact;

    public string $listSearch = '';

    /** @var array<int, int> */
    public array $selectedLists = [];

    public function mount(Contact $contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @return Collection<int, MailingList>
     */
    #[Computed]
    public function contactLists(): Collection
    {
        return $this->contact->mailingLists()->withCount('contacts')->get();
    }

    /**
     * @return Collection<int, MailingList>
     */
    #[Computed]
    public function availableLists(): Collection
    {
        return MailingList::query()
            ->whereNotIn('id', $this->contact->mailingLists()->pluck('mailing_lists.id'))
            ->when($this->listSearch !== '', fn ($q) => $q->search($this->listSearch))
            ->limit(10)
            ->get();
    }

    public function openAddToList(): void
    {
        $this->listSearch = '';
        $this->selectedLists = [];

        $this->dispatch('modal-show', name: 'add-to-list');
    }

    public function addToLists(AddContactToMailingListAction $action): void
    {
        $action->handle($this->contact, $this->selectedLists);

        $this->selectedLists = [];
        $this->listSearch = '';

        $this->dispatch('modal-close', name: 'add-to-list');
        Flux::toast(variant: 'success', text: __('Contact added to lists.'));
    }

    public function removeFromList(MailingList $list, RemoveContactFromMailingListAction $action): void
    {
        $action->handle($this->contact, $list);

        Flux::toast(variant: 'success', text: __('Contact removed from list.'));
    }
}
