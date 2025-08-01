<?php

namespace App\Enums;

enum BookStatus: string
{
    case PUBLISH = 'PUBLISH';
    case MEAP = 'MEAP';

    public static function fromString(string $status): ?self
    {
        return match (strtoupper($status)) {
            'PUBLISH' => self::PUBLISH,
            'MEAP' => self::MEAP,
            default => null,
        };
    }
}
