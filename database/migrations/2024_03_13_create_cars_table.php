<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->string('registration_number')->unique();
            $table->year('year');
            $table->decimal('price_per_day', 8, 2);
            $table->text('description')->nullable();
            $table->string('status')->default('not_rented');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}; 