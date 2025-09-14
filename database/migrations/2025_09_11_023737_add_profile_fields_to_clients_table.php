<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->after('driving_license_expiry');
            $table->json('preferences')->nullable()->after('profile_picture');
            $table->json('documents')->nullable()->after('preferences');
            $table->text('bio')->nullable()->after('documents');
            $table->string('occupation')->nullable()->after('bio');
            $table->string('company')->nullable()->after('occupation');
            $table->string('nationality')->nullable()->after('company');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('nationality');
            $table->string('emergency_contact_name')->nullable()->after('gender');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'profile_picture',
                'preferences',
                'documents',
                'bio',
                'occupation',
                'company',
                'nationality',
                'gender',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relation',
            ]);
        });
    }
};