<?php

namespace App\Enums;

enum ActiveStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => '有効',
            self::INACTIVE => '無効',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
