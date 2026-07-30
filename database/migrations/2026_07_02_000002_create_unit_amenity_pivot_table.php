<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('unit_amenity')) {
            return;
        }

        Schema::create('unit_amenity', function (Blueprint $table) {
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('amenity_id')->constrained('amenities')->onDelete('cascade');
            $table->primary(['unit_id', 'amenity_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_amenity');
    }
};
