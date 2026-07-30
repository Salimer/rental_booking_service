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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 150)->unique();
            $table->string('name_en', 150)->unique();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('name_ar', 150);
            $table->string('name_en', 150);
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->unique(['country_id', 'name_en'], 'rental_city_unique_en');
            $table->unique(['country_id', 'name_ar'], 'rental_city_unique_ar');
        });

        Schema::table('properties', function (Blueprint $table) {
            // Drop old string city column
            $table->dropColumn('city');
            // Add reference to cities
            $table->unsignedBigInteger('city_id')->nullable()->after('type_id');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
            $table->string('city', 100)->nullable();
        });

        Schema::dropIfExists('cities');
        Schema::dropIfExists('countries');
    }
};
