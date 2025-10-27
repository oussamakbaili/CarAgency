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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rental_id'); // Référence à la réservation
            $table->unsignedBigInteger('sender_id'); // ID de l'expéditeur
            $table->string('sender_type'); // 'client' ou 'agency'
            $table->unsignedBigInteger('receiver_id'); // ID du destinataire
            $table->string('receiver_type'); // 'client' ou 'agency'
            $table->text('message'); // Contenu du message
            $table->boolean('is_read')->default(false); // Statut de lecture
            $table->timestamp('read_at')->nullable(); // Date de lecture
            $table->json('attachments')->nullable(); // Pièces jointes (images, documents)
            $table->string('message_type')->default('text'); // text, image, document, system
            $table->timestamps();

            // Index pour les performances
            $table->index(['rental_id', 'created_at']);
            $table->index(['sender_id', 'sender_type']);
            $table->index(['receiver_id', 'receiver_type']);
            $table->index(['is_read', 'created_at']);

            // Clés étrangères
            $table->foreign('rental_id')->references('id')->on('rentals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
