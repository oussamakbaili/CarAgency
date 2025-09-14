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
        Schema::table('special_offers', function (Blueprint $table) {
            $table->enum('offer_type', ['regular', 'flash', 'promo_code'])->default('regular')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->dropColumn('offer_type');
        });
    }
};