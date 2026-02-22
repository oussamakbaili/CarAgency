<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->text('message')->nullable()->after('subject');
            $table->json('replies')->nullable()->after('message');
        });
    }

    public function down()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn(['message', 'replies']);
        });
    }
};