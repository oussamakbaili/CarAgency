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
            $table->integer('stock_quantity')->default(1)->after('description');
            $table->integer('available_stock')->default(1)->after('stock_quantity');
            $table->boolean('track_stock')->default(true)->after('available_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['stock_quantity', 'available_stock', 'track_stock']);
        });
    }
};
