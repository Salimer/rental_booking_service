<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('availabilities')) {
            return;
        }

        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->date('date');
            $table->unsignedInteger('available_quantity')->default(1);
            $table->decimal('override_price', 10, 2)->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->string('block_reason')->nullable();
            $table->timestamps();

            $table->unique(['unit_id', 'date'], 'unit_date_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('availabilities');
    }
};
