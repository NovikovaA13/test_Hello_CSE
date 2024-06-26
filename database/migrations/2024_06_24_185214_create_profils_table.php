<?php

use App\Enums\StatutEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nom');
            $table->string('prenom');
            $table->string('image');
            $table->enum('statut', [StatutEnum::ACTIF->value, StatutEnum::INATTENTE->value, StatutEnum::INACTIF->value])->default(StatutEnum::INATTENTE->value);

            //$table->enum('statut', StatutEnum::cases())->default(StatutEnum::INATTENTE);
            //$table->enum('statut', ['actif', StatutEnum::INATTENTE->value, 'inactif'])->default(StatutEnum::INATTENTE);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
