<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('booking_confirmations')) {
            return;
        }

        Schema::create('booking_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('confirmation_number', 100)->unique();
            $table->text('qr_payload')->nullable();
            $table->string('voucher_file_path')->nullable();
            $table->enum('status', ['issued', 'used', 'cancelled'])->default('issued');
            $table->dateTime('issued_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_confirmations');
    }
};
