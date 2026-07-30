<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('manual_locks')) {
            return;
        }

        Schema::create('manual_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason', 255)->nullable();
            $table->unsignedBigInteger('created_by_vendor_id')->nullable();
            $table->boolean('created_by_admin')->default(false);
            $table->timestamps();

            $table->index(['unit_id', 'start_date', 'end_date'], 'rental_manual_locks_unit_dates_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('manual_locks');
    }
};
