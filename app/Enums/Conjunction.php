<?php

namespace App\Enums;

enum Conjunction: string
{
    case AMPERSAND = '&';
    case AND_LOWERCASE = 'and';
    case AND_TITLECASE = 'And';
    case AND_UPPERCASE = 'AND';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $conjunction): bool
    {
        return in_array($conjunction, self::values(), true);
    }
}
