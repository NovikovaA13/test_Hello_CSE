<?php

namespace App\Models;

use App\Enums\StatutEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'prenom', 'image'];
    protected $casts = [
        'statut' => StatutEnum::class,
    ];
}
