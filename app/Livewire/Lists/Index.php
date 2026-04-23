<?php

declare(strict_types=1);

namespace App\Livewire\Lists;

use App\Actions\DestroyMailingListAction;
use App\Actions\StoreMailingListAction;
use App\Http\Requests\MailingListRequest;
use App\Models\MailingList;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Lists')]
final class Index extends Component
{
    public string $search = '';

    public ?MailingList $confirmingDelete = null;

    public string $name = '';

    public string $description = '';

    public string $icon = 'envelope';

    public string $color = 'zinc';

    /**
     * @return array{total: int, total_members: int, last_updated: ?string}
     */
    #[Computed]
    public function stats(): array
    {
        return Cache::tags(['mailing_lists', 'mailing_lists:stats'])->flexible('mailing_lists:stats', [300, 600], function (): array {
            /** @var object{total: int, total_members: int} $row */
            $row = DB::selectOne('
                SELECT
                    COUNT(DISTINCT ml.id) as total,
                    COUNT(cml.id) as total_members
                FROM mailing_lists ml
                LEFT JOIN contact_mailing_list cml ON cml.mailing_list_id = ml.id
            ');

            $lastUpdated = MailingList::query()->latest('updated_at')->value('updated_at');

            return [
                'total' => $row->total,
                'total_members' => $row->total_members,
                'last_updated' => $lastUpdated?->translatedFormat('d M Y, H:i'),
            ];
        });
    }

    /**
     * @return Collection<int, MailingList>
     */
    #[Computed]
    public function lists(): Collection
    {
        return MailingList::query()
            ->when($this->search !== '', fn ($q) => $q->search($this->search))
            ->withCount('contacts')
            ->latest()
            ->get();
    }

    public function confirmDelete(MailingList $list): void
    {
        $this->confirmingDelete = $list;

        $this->dispatch('modal-show', name: 'confirm-delete-list');
    }

    public function delete(DestroyMailingListAction $action): void
    {
        if ($this->confirmingDelete === null) {
            return;
        }

        $action->handle($this->confirmingDelete);

        $this->confirmingDelete = null;

        $this->dispatch('modal-close', name: 'confirm-delete-list');
        Flux::toast(variant: 'success', text: __('List deleted.'));
    }

    public function create(): void
    {
        $this->name = '';
        $this->description = '';
        $this->icon = 'envelope';
        $this->color = 'zinc';
        $this->resetErrorBag();

        $this->dispatch('modal-show', name: 'list-form');
    }

    public function save(StoreMailingListAction $action): void
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validate((new MailingListRequest())->rules());

        $list = $action->handle($validated);

        $this->name = '';
        $this->description = '';
        $this->icon = 'envelope';
        $this->color = 'zinc';

        $this->dispatch('modal-close', name: 'list-form');
        Flux::toast(variant: 'success', text: __('List created.'));

        $this->redirectRoute('lists.show', $list, navigate: true);
    }
}
