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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->integer('rating')->unsigned(); // 1-5 stars
            $table->text('comment')->nullable();
            $table->string('title')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['agency_id', 'rating']);
            $table->index(['client_id', 'created_at']);
            $table->index(['rental_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
