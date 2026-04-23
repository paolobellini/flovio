<?php

declare(strict_types=1);

namespace App\Livewire\Templates;

use App\Actions\DestroyTemplateAction;
use App\Models\Template;
use Flux\Flux;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Templates')]
final class Index extends Component
{
    public string $search = '';

    public ?Template $confirmingDelete = null;

    public function confirmDelete(Template $template): void
    {
        $this->confirmingDelete = $template;

        $this->dispatch('modal-show', name: 'confirm-delete-template');
    }

    public function delete(DestroyTemplateAction $action): void
    {
        $action->handle($this->confirmingDelete);

        $this->confirmingDelete = null;

        $this->dispatch('modal-close', name: 'confirm-delete-template');
        Flux::toast(variant: 'success', text: __('Template deleted.'));
    }

    /**
     * @return Collection<int, Template>
     */
    #[Computed]
    public function templates(): Collection
    {
        /** @var array<int, array<string, mixed>> $cached */
        $cached = Cache::tags(['templates'])->flexible(
            "templates:list:{$this->search}",
            [600, 1200],
            fn () => Template::query()
                ->when($this->search !== '', fn ($q) => $q->search($this->search))
                ->latest()
                ->get()
                ->toArray(),
        );

        return Template::hydrate($cached);
    }
}
