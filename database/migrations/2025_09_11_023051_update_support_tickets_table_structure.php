<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            // Make client_id nullable since we're using agency_id for agency support
            $table->unsignedBigInteger('client_id')->nullable()->change();
            
            // Make rental_id nullable since it's not always needed
            $table->unsignedBigInteger('rental_id')->nullable()->change();
            
            // Make ticket_number nullable since we're using auto-increment ID
            $table->string('ticket_number')->nullable()->change();
            
            // Make description nullable since we're using message field
            $table->text('description')->nullable()->change();
            
            // Make category nullable since we're not using it in the new system
            $table->string('category')->nullable()->change();
            
            // Make resolved_at and closed_at nullable
            $table->timestamp('resolved_at')->nullable()->change();
            $table->timestamp('closed_at')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            // Revert changes if needed
            $table->unsignedBigInteger('client_id')->nullable(false)->change();
            $table->unsignedBigInteger('rental_id')->nullable(false)->change();
            $table->string('ticket_number')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->string('category')->nullable(false)->change();
            $table->timestamp('resolved_at')->nullable(false)->change();
            $table->timestamp('closed_at')->nullable(false)->change();
        });
    }
};