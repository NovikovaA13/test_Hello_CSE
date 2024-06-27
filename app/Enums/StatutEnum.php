<?php

namespace App\Enums;

enum StatutEnum: string {
    case ACTIF = 'actif';
    case INATTENTE = 'inattente';
    case INACTIF = 'inactif';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
