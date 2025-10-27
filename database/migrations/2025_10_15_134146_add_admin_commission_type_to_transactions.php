<?php

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
        // Ajouter le type de transaction admin_commission
        // Cette migration met à jour les constantes dans le modèle Transaction
        // et s'assure que la base de données peut gérer ce nouveau type
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas de rollback nécessaire car on n'ajoute pas de colonnes
    }
};