<?php

namespace App\Enums;

enum StatutEnum: string {
    case ACTIF = 'actif';
    case INATTENTE = 'inattente';
    case INACTIF = 'inactif';
}
