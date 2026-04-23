<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Template;

final readonly class DestroyTemplateAction
{
    public function handle(Template $template): void
    {
        $template->delete();
    }
}
