<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('agency_name');
            $table->string('responsable_name');
            $table->string('email');
            $table->string('phone');
            
            // Additional agency information
            $table->string('address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('commercial_register_number');
            
            // Responsable additional information
            $table->string('responsable_phone');
            $table->string('responsable_position');
            $table->string('responsable_identity_number');
            
            // Documents
            $table->string('commercial_register_doc')->nullable(); // Path to document
            $table->string('identity_doc')->nullable(); // Path to document
            $table->string('tax_doc')->nullable(); // Path to document
            $table->text('additional_docs')->nullable(); // JSON array of additional document paths
            
            // Business Information
            $table->integer('years_in_business')->nullable();
            $table->text('business_description')->nullable();
            $table->integer('estimated_fleet_size');
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agencies');
    }
}; 