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
        Schema::table('transactions', function (Blueprint $table) {
            // Update the enum to include withdrawal_request
            $table->enum('type', ['rental_payment', 'withdrawal', 'withdrawal_request', 'refund', 'commission', 'penalty'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Revert back to original enum
            $table->enum('type', ['rental_payment', 'withdrawal', 'refund', 'commission', 'penalty'])->change();
        });
    }
};
