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
        Schema::create('car_external_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->string('site_name'); // Nom du site externe (ex: "Site A", "Site B")
            $table->string('site_url'); // URL du site externe (ex: "https://site-a.com")
            $table->string('api_url'); // URL de l'API du site externe (ex: "https://site-a.com/api")
            $table->string('api_token')->nullable(); // Token d'authentification pour l'API
            $table->string('external_car_id')->nullable(); // ID du véhicule sur le site externe
            $table->boolean('is_active')->default(true); // Activer/désactiver la vérification
            $table->text('notes')->nullable(); // Notes optionnelles
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('car_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_external_sites');
    }
};
