<?php

declare(strict_types=1);

namespace App\Livewire\Templates;

use App\Models\Template;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Templates')]
final class Index extends Component
{
    public string $search = '';

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
