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
            $table->integer('cancellation_count')->default(0)->after('status');
            $table->timestamp('last_cancellation_at')->nullable()->after('cancellation_count');
            $table->boolean('is_suspended')->default(false)->after('last_cancellation_at');
            $table->timestamp('suspended_at')->nullable()->after('is_suspended');
            $table->text('suspension_reason')->nullable()->after('suspended_at');
            $table->integer('max_cancellations')->default(3)->after('suspension_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_count',
                'last_cancellation_at',
                'is_suspended',
                'suspended_at',
                'suspension_reason',
                'max_cancellations'
            ]);
        });
    }
};