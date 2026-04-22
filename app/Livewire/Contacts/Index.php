<?php

declare(strict_types=1);

namespace App\Livewire\Contacts;

use App\Actions\DestroyContactAction;
use App\Models\Contact;
use Flux\Flux;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Contacts')]
final class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public ?Contact $confirmingDelete = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(Contact $contact): void
    {
        $this->confirmingDelete = $contact;

        $this->dispatch('modal-show', name: 'confirm-delete-contact');
    }

    public function delete(DestroyContactAction $action): void
    {
        if ($this->confirmingDelete === null) {
            return;
        }

        $action->handle($this->confirmingDelete);

        $this->confirmingDelete = null;

        $this->dispatch('modal-close', name: 'confirm-delete-contact');
        Flux::toast(variant: 'success', text: __('Contact deleted.'));
    }

    /**
     * @return array{total: int, subscribed: int, unsubscribed: int}
     */
    #[Computed]
    public function stats(): array
    {
        return Cache::tags(['contacts', 'contacts:stats'])->flexible('contacts:stats', [300, 600], function (): array {
            /** @var object{total: int, subscribed: int, unsubscribed: int} $row */
            $row = DB::selectOne("
                SELECT
                    COUNT(*) as total,
                    COUNT(*) FILTER (WHERE status = 'subscribed') as subscribed,
                    COUNT(*) FILTER (WHERE status = 'unsubscribed') as unsubscribed
                FROM contacts
            ");

            return [
                'total' => $row->total,
                'subscribed' => $row->subscribed,
                'unsubscribed' => $row->unsubscribed,
            ];
        });
    }

    /**
     * @return LengthAwarePaginator<int, Contact>
     */
    #[Computed]
    public function contacts(): LengthAwarePaginator
    {
        return Contact::query()
            ->when($this->search !== '', fn ($query) => $query->search($this->search))
            ->when($this->status !== '', fn ($query) => $query->status($this->status))
            ->latest()
            ->paginate(10);
    }
}
