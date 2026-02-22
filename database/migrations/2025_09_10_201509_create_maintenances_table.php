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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['routine', 'repair', 'inspection', 'emergency', 'other'])->default('routine');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->date('scheduled_date');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('garage_name')->nullable();
            $table->string('garage_contact')->nullable();
            $table->text('notes')->nullable();
            $table->integer('mileage_at_service')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
