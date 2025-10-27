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
            $table->boolean('featured')->default(false)->after('status');
            $table->boolean('show_on_homepage')->default(true)->after('featured');
            $table->integer('homepage_priority')->default(0)->after('show_on_homepage');
            $table->timestamp('featured_at')->nullable()->after('homepage_priority');
        });

        Schema::table('agencies', function (Blueprint $table) {
            $table->boolean('featured')->default(false)->after('status');
            $table->boolean('show_on_homepage')->default(true)->after('featured');
            $table->integer('homepage_priority')->default(0)->after('show_on_homepage');
            $table->timestamp('featured_at')->nullable()->after('homepage_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['featured', 'show_on_homepage', 'homepage_priority', 'featured_at']);
        });

        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn(['featured', 'show_on_homepage', 'homepage_priority', 'featured_at']);
        });
    }
};

