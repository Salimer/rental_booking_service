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
        Schema::create('neighborhoods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('name_ar', 150);
            $table->string('name_en', 150);
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unique(['city_id', 'name_en'], 'rental_neighborhood_unique_en');
            $table->unique(['city_id', 'name_ar'], 'rental_neighborhood_unique_ar');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('neighborhood_id')->nullable()->after('city_id');
            $table->foreign('neighborhood_id')->references('id')->on('neighborhoods')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['neighborhood_id']);
            $table->dropColumn('neighborhood_id');
        });

        Schema::dropIfExists('neighborhoods');
    }
};
