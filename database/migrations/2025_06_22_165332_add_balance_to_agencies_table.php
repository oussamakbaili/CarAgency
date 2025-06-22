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
            $table->decimal('balance', 12, 2)->default(0)->after('status');
            $table->decimal('total_earnings', 12, 2)->default(0)->after('balance');
            $table->decimal('pending_earnings', 12, 2)->default(0)->after('total_earnings');
            $table->timestamp('last_payout_at')->nullable()->after('pending_earnings');
            $table->decimal('commission_rate', 5, 2)->default(10.00)->after('last_payout_at'); // Platform commission percentage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['balance', 'total_earnings', 'pending_earnings', 'last_payout_at', 'commission_rate']);
        });
    }
};
