<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Template;
use Illuminate\Support\Facades\Cache;

final class TemplateObserver
{
    public function created(Template $template): void
    {
        $this->flush();
    }

    public function updated(Template $template): void
    {
        $this->flush();
    }

    public function deleted(Template $template): void
    {
        $this->flush();
    }

    private function flush(): void
    {
        Cache::tags(['templates'])->flush();
    }
}
