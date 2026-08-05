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
        Schema::create('properties', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->decimal('price', 12, 2);
    $table->string('address')->nullable();
    $table->integer('bedrooms')->nullable();
    $table->integer('bathrooms')->nullable();
    $table->integer('surface')->nullable(); 
    $table->string('status')->default('available'); // available, sold, rented
    $table->json('image')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
