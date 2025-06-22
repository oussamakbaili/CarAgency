<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Add missing fields
            if (!Schema::hasColumn('cars', 'color')) {
                $table->string('color')->nullable()->after('description');
            }
            if (!Schema::hasColumn('cars', 'fuel_type')) {
                $table->string('fuel_type')->nullable()->after('color');
            }
        });

        // Update existing cars with 'not_rented' status to 'available'
        DB::table('cars')->where('status', 'not_rented')->update(['status' => 'available']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['color', 'fuel_type']);
        });
    }
}; 