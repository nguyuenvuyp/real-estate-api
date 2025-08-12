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
        $table->enum('property_type', ['apartment', 'house', 'villa', 'office', 'land']);
        $table->enum('status', ['available', 'sold', 'rented', 'pending'])->default('available');
        $table->decimal('price', 15, 2);
        $table->decimal('area', 10, 2);
        $table->integer('bedrooms')->default(0);
        $table->integer('bathrooms')->default(0);
        $table->integer('floors')->default(1);
        $table->text('address');
        $table->string('city', 100);
        $table->string('district', 100);
        $table->string('postal_code', 20)->nullable();
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        $table->integer('year_built')->nullable();
        $table->json('features')->nullable();
        $table->json('images')->nullable();
        $table->string('contact_name');
        $table->string('contact_phone', 20);
        $table->string('contact_email')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
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
