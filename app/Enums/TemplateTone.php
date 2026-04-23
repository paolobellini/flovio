<?php

declare(strict_types=1);

namespace App\Enums;

enum TemplateTone: string
{
    case Professional = 'professional';
    case Casual = 'casual';
    case Elegant = 'elegant';

    public function label(): string
    {
        return match ($this) {
            self::Professional => __('Professional'),
            self::Casual => __('Casual'),
            self::Elegant => __('Elegant'),
        };
    }
}
