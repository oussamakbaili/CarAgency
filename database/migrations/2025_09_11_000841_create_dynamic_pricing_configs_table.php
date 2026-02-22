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
        Schema::create('dynamic_pricing_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');
            $table->boolean('enabled')->default(false);
            $table->decimal('peak_hour_multiplier', 3, 2)->default(1.20);
            $table->decimal('weekend_multiplier', 3, 2)->default(1.10);
            $table->decimal('last_minute_multiplier', 3, 2)->default(0.90);
            $table->integer('demand_threshold')->default(80); // Percentage
            $table->json('peak_hours')->nullable(); // Array of peak hours
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_pricing_configs');
    }
};
