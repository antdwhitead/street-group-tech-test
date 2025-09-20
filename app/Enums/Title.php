<?php

declare(strict_types=1);

namespace App\Enums;

enum Title: string
{
    case MR = 'Mr';
    case MRS = 'Mrs';
    case MISS = 'Miss';
    case MS = 'Ms';
    case DR = 'Dr';
    case PROF = 'Prof';
    case SIR = 'Sir';
    case LORD = 'Lord';
    case LADY = 'Lady';
    case MISTER = 'Mister';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $title): bool
    {
        return in_array($title, self::values(), true);
    }
}
