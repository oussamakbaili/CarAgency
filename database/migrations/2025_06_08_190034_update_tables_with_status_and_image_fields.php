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
        Schema::table('agencies', function (Blueprint $table) {
            if (!Schema::hasColumn('agencies', 'status')) {
                $table->string('status')->default('pending');
            }
        });

        Schema::table('cars', function (Blueprint $table) {
            if (!Schema::hasColumn('cars', 'status')) {
                $table->string('status')->default('available');
            }
            if (!Schema::hasColumn('cars', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['status', 'image']);
        });
    }
};
