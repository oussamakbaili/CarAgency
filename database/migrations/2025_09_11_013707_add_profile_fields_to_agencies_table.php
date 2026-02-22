<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->after('agency_name');
            $table->text('description')->nullable()->after('registration_number');
            $table->string('country')->nullable()->after('postal_code');
            $table->decimal('latitude', 10, 8)->nullable()->after('country');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->json('opening_hours')->nullable()->after('longitude');
            $table->json('documents')->nullable()->after('opening_hours');
            $table->string('profile_picture')->nullable()->after('documents');
        });
    }

    public function down()
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn([
                'registration_number',
                'description',
                'country',
                'latitude',
                'longitude',
                'opening_hours',
                'documents',
                'profile_picture'
            ]);
        });
    }
};