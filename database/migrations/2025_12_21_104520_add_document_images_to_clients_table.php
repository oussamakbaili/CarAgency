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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('cin_recto')->nullable()->after('cin');
            $table->string('cin_verso')->nullable()->after('cin_recto');
            $table->string('driving_license_image')->nullable()->after('driving_license_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['cin_recto', 'cin_verso', 'driving_license_image']);
        });
    }
};
