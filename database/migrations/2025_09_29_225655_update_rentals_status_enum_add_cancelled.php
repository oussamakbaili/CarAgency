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
        // Update the enum to include 'cancelled'
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'active', 'completed', 'rejected', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'cancelled' from the enum
        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('pending', 'active', 'completed', 'rejected') DEFAULT 'pending'");
    }
};