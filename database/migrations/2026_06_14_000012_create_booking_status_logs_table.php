<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('booking_status_logs')) {
            return;
        }

        Schema::create('booking_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->string('changed_by_type', 50)->nullable();
            $table->string('changed_by_role', 50)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'created_at'], 'status_logs_booking_created_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_status_logs');
    }
};
