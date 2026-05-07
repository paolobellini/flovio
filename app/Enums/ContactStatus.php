<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactStatus: string
{
    case Subscribed = 'subscribed';
    case Unsubscribed = 'unsubscribed';

    public function label(): string
    {
        return match ($this) {
            self::Subscribed => __('Subscribed'),
            self::Unsubscribed => __('Unsubscribed'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Subscribed => 'green',
            self::Unsubscribed => 'zinc',
        };
    }
}
