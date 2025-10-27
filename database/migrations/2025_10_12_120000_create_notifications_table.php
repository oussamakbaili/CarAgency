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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->string('type'); // 'booking', 'payment', 'maintenance', etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->default('bell'); // Icon name
            $table->string('icon_color')->default('blue'); // blue, green, orange, red
            $table->string('action_url')->nullable(); // URL to navigate when clicked
            $table->foreignId('related_id')->nullable(); // ID of related entity (booking_id, payment_id, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['agency_id', 'is_read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

