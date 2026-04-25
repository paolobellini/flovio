<?php

declare(strict_types=1);

namespace App\Livewire\Templates;

use App\Actions\DestroyTemplateAction;
use App\Actions\StoreTemplateAction;
use App\Actions\UpdateTemplateAction;
use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Template Editor')]
final class Editor extends Component
{
    public ?Template $template = null;

    public string $name = '';

    public string $description = '';

    public string $primary_color = '#7B2D42';

    public string $layout = 'single';

    public string $tone = 'professional';

    public string $prompt = '';

    public function mount(?Template $template = null): void
    {
        if ($template?->exists) {
            $this->template = $template;
            $this->name = $template->name;
            $this->description = $template->description ?? '';
            $this->primary_color = $template->primary_color;
            $this->layout = $template->layout->value;
            $this->tone = $template->tone->value;
            $this->prompt = $template->last_prompt ?? '';
        }
    }

    #[Computed]
    public function isEditing(): bool
    {
        return $this->template !== null;
    }

    public function confirmDelete(): void
    {
        $this->dispatch('modal-show', name: 'confirm-delete-template');
    }

    public function delete(DestroyTemplateAction $action): void
    {
        if ($this->template === null) {
            return;
        }

        $action->handle($this->template);

        $this->dispatch('modal-close', name: 'confirm-delete-template');
        Flux::toast(text: __('Template deleted.'), variant: 'success');

        $this->redirectRoute('templates.index', navigate: true);
    }

    public function save(StoreTemplateAction $store, UpdateTemplateAction $update): void
    {
        /** @var array<string, mixed> $validated */
        $validated = $this->validate(new TemplateRequest()->rules());

        if ($this->isEditing() && $this->template !== null) {
            $update->handle($this->template, $validated);
            Flux::toast(text: __('Template updated.'), variant: 'success');
        } else {
            $template = $store->handle($validated);
            Flux::toast(text: __('Template created.'), variant: 'success');

            $this->redirectRoute('templates.edit', $template, navigate: true);
        }
    }
}
