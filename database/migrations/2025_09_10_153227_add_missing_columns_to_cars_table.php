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
        Schema::table('cars', function (Blueprint $table) {
            // Add only the missing columns that don't exist yet
            if (!Schema::hasColumn('cars', 'maintenance_due')) {
                $table->date('maintenance_due')->nullable()->after('track_stock');
            }
            if (!Schema::hasColumn('cars', 'last_maintenance')) {
                $table->date('last_maintenance')->nullable()->after('maintenance_due');
            }
            if (!Schema::hasColumn('cars', 'mileage')) {
                $table->integer('mileage')->nullable()->after('last_maintenance');
            }
            if (!Schema::hasColumn('cars', 'transmission')) {
                $table->string('transmission')->nullable()->after('mileage');
            }
            if (!Schema::hasColumn('cars', 'seats')) {
                $table->integer('seats')->nullable()->after('transmission');
            }
            if (!Schema::hasColumn('cars', 'engine_size')) {
                $table->string('engine_size')->nullable()->after('seats');
            }
            if (!Schema::hasColumn('cars', 'features')) {
                $table->json('features')->nullable()->after('engine_size'); // JSON field for additional features
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'maintenance_due',
                'last_maintenance',
                'mileage',
                'transmission',
                'seats',
                'engine_size',
                'features'
            ]);
        });
    }
};
