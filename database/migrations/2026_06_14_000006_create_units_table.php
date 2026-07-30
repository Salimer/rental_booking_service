<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('units')) {
            return;
        }

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('name_ar', 150);
            $table->string('name_en', 150)->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->enum('pricing_mode', ['per_night', 'per_hour', 'per_slot'])->default('per_night');
            $table->unsignedInteger('max_guests')->default(1);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('weekend_price', 10, 2)->nullable();
            $table->json('images')->nullable();
            $table->json('amenities')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};
