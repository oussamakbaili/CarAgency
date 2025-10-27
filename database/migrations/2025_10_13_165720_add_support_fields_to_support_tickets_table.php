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
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->foreignId('last_reply_by')->nullable()->after('assigned_to')->constrained('users')->nullOnDelete();
            $table->timestamp('last_reply_at')->nullable()->after('last_reply_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropForeign(['last_reply_by']);
            $table->dropColumn(['assigned_to', 'last_reply_by', 'last_reply_at']);
        });
    }
};
