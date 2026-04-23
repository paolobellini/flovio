<?php

declare(strict_types=1);

namespace App\Livewire\Lists;

use App\Actions\UpdateMailingListAction;
use App\Http\Requests\MailingListRequest;
use App\Models\MailingList;
use Flux\Flux;
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
