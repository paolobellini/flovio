<?php

declare(strict_types=1);

namespace App\Livewire\Lists;

use App\Actions\AddMembersToMailingListAction;
use App\Actions\DestroyMailingListAction;
use App\Actions\RemoveMemberFromMailingListAction;
use App\Actions\UpdateMailingListAction;
use App\Http\Requests\MailingListRequest;
use App\Models\Contact;
use App\Models\MailingList;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('List')]
final class Show extends Component
{
    public MailingList $list;

    public string $name = '';

    public string $description = '';

    public string $icon = 'envelope';

    public string $color = 'zinc';

    public string $memberSearch = '';

    public string $addSearch = '';

    /** @var array<int, int> */
    public array $selectedContacts = [];

    public function mount(MailingList $list): void
    {
        $this->list = $list;
        $this->name = $list->name;
        $this->description = $list->description ?? '';
        $this->icon = $list->icon;
        $this->color = $list->color;
    }

    /**
     * @return array{members: int, campaigns_sent: int, avg_open_rate: string, avg_click_rate: string}
     */
    #[Computed]
    public function stats(): array
    {
        return Cache::tags(['mailing_lists', "mailing_lists:{$this->list->id}:stats"])->flexible(
            "mailing_lists:{$this->list->id}:stats",
            [300, 600],
            fn (): array => [
                'members' => $this->list->contacts()->count(),
                'campaigns_sent' => 0,
                'avg_open_rate' => '-',
                'avg_click_rate' => '-',
            ],
        );
    }

    /**
     * @return Collection<int, Contact>
     */
    #[Computed]
    public function members(): Collection
    {
        return $this->list->contacts()
            ->when($this->memberSearch !== '', fn ($q) => $q->search($this->memberSearch))
            ->latest('contact_mailing_list.created_at')
            ->limit(5)
            ->get();
    }

    /**
     * @return Collection<int, Contact>
     */
    #[Computed]
    public function availableContacts(): Collection
    {
        return Contact::query()
            ->whereNotIn('id', $this->list->contacts()->pluck('contacts.id'))
            ->when($this->addSearch !== '', fn ($q) => $q->search($this->addSearch))
            ->limit(10)
            ->get();
    }

    public function openAddMembers(): void
    {
        $this->addSearch = '';
        $this->selectedContacts = [];

        $this->dispatch('modal-show', name: 'add-members');
    }

    public function addMembers(AddMembersToMailingListAction $action): void
    {
        $action->handle($this->list, $this->selectedContacts);

        $this->selectedContacts = [];
        $this->addSearch = '';

        $this->dispatch('modal-close', name: 'add-members');
        Flux::toast(variant: 'success', text: __('Members added.'));
    }

    public function removeMember(Contact $contact, RemoveMemberFromMailingListAction $action): void
    {
        $action->handle($this->list, $contact);

        Flux::toast(variant: 'success', text: __('Member removed.'));
    }

    public function confirmDelete(): void
    {
        $this->dispatch('modal-show', name: 'confirm-delete-list');
    }

    public function delete(DestroyMailingListAction $action): void
    {
        $action->handle($this->list);

        $this->dispatch('modal-close', name: 'confirm-delete-list');
        Flux::toast(variant: 'success', text: __('List deleted.'));

        $this->redirectRoute('lists.index', navigate: true);
    }

    public function edit(): void
    {
        $this->name = $this->list->name;
        $this->description = $this->list->description ?? '';
        $this->icon = $this->list->icon;
        $this->color = $this->list->color;
        $this->resetErrorBag();

        $this->dispatch('modal-show', name: 'list-form');
    }

    public function save(UpdateMailingListAction $action): void
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validate((new MailingListRequest())->rules());

        $action->handle($this->list, $validated);

        $this->dispatch('modal-close', name: 'list-form');
        Flux::toast(variant: 'success', text: __('List updated.'));
    }
}
