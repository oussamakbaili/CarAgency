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
            if (!Schema::hasColumn('clients', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('clients', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('city');
            }
            if (!Schema::hasColumn('clients', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('postal_code');
            }
            if (!Schema::hasColumn('clients', 'driving_license_number')) {
                $table->string('driving_license_number')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('clients', 'driving_license_expiry')) {
                $table->date('driving_license_expiry')->nullable()->after('driving_license_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'postal_code',
                'date_of_birth',
                'driving_license_number',
                'driving_license_expiry'
            ]);
        });
    }
};