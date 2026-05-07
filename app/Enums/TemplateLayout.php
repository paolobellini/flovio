<?php

declare(strict_types=1);

namespace App\Enums;

enum TemplateLayout: string
{
    case Single = 'single';
    case TwoColumn = 'two-column';
    case Hero = 'hero';

    public function label(): string
    {
        return match ($this) {
            self::Single => __('Single column'),
            self::TwoColumn => __('Two columns'),
            self::Hero => __('Hero image'),
        };
    }
}
